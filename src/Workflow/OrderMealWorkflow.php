<?php

declare(strict_types=1);

namespace Notdefine\Workflow\Workflow;

use NeuronAI\Workflow\Edge;
use NeuronAI\Workflow\Workflow;
use NeuronAI\Workflow\WorkflowState;
use Notdefine\Workflow\Agent\MealOrderAgent;
use Notdefine\Workflow\InspectorTrait;
use Notdefine\Workflow\Node\DetermineCustomerNode;
use Notdefine\Workflow\Node\DetermineMealNode;
use Notdefine\Workflow\Node\NotFinished;
use Notdefine\Workflow\Node\OrderCompleteNode;
use Notdefine\Workflow\StructuredOutput\CustomerStructure;
use Notdefine\Workflow\StructuredOutput\MealStructure;

class OrderMealWorkflow extends Workflow
{
    use InspectorTrait;

    public const KI_RESPONSE = 'response';

    public function nodes(): array
    {
        $mealOrderAgentStructured = MealOrderAgent::make();
        $mealOrderAgentChat = MealOrderAgent::make();

        /** @var MealOrderAgent $mealOrderAgentStructured */
        $mealOrderAgentStructured = $this->addAgentMonitoring($mealOrderAgentStructured);
        /** @var MealOrderAgent $mealOrderAgentChat */
        $mealOrderAgentChat = $this->addAgentMonitoring($mealOrderAgentChat);

        // Nodes do the work, Edges tell what to do next.
        return [
            new DetermineCustomerNode(),
            new DetermineMealNode(
                $mealOrderAgentChat,
                $mealOrderAgentStructured,
            ),
            new NotFinished(),
            new OrderCompleteNode(),
        ];
    }

    private function isMealComplete(WorkflowState $state): bool
    {
        /** @var MealStructure|null $meal */
        $meal = $state->get(MealStructure::STATE_KEY);
        if ($meal === null) {
            echo '[MealStructure unknown]' . PHP_EOL;
            return false;
        }
        return $meal->isComplete();
    }

    private function isCustomerStateDone(WorkflowState  $state): bool
    {
        /** @var CustomerStructure|null $customer */
        $customer = $state->get(CustomerStructure::STATE_KEY);
        if ($customer === null) {
            echo '[CustomerStructure unknown]' . PHP_EOL;
            return false;
        }

        return $customer->isComplete();
    }

    public function edges(): array
    {
        return [
            new Edge(
                DetermineCustomerNode::class,
                DetermineMealNode::class,
                function (WorkflowState $state): bool {
                    return $this->isCustomerStateDone($state);
                },
            ),

            new Edge(
                DetermineCustomerNode::class,
                NotFinished::class,
                function (WorkflowState $state): bool {
                    return !$this->isCustomerStateDone($state);
                },
            ),

            new Edge(
                DetermineMealNode::class,
                OrderCompleteNode::class,
                function (WorkflowState $state): bool {
                    return $this->isMealComplete($state);
                },
            ),

            new Edge(
                DetermineMealNode::class,
                NotFinished::class,
                function (WorkflowState $state): bool {
                    return !$this->isMealComplete($state);
                },
            ),
        ];
    }

    protected function start(): string
    {
        return DetermineCustomerNode::class;
    }

    protected function end(): array
    {
        return [
            NotFinished::class,
            OrderCompleteNode::class,
        ];
    }

}

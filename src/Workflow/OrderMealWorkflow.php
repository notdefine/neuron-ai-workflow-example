<?php

declare(strict_types=1);

namespace Notdefine\Workflow\Workflow;

use NeuronAI\Workflow\Edge;
use NeuronAI\Workflow\Workflow;
use NeuronAI\Workflow\WorkflowState;
use Notdefine\Workflow\Node\DetermineCustomerNode;
use Notdefine\Workflow\Node\DetermineMealNode;
use Notdefine\Workflow\Node\OrderCompleteNode;
use Notdefine\Workflow\Node\OrderIncompleteNode;
use Notdefine\Workflow\StructuredOutput\CustomerStructure;


class OrderMealWorkflow extends Workflow
{
    public const KI_RESPONSE = 'response';
    public const CUSTOMER_OBJECT = 'customerObject';
    public const MEAL_OBJECT = 'chosenMeal';

    public function nodes(): array
    {
        // Nodes do the work, Edges tell what to do next.
        return [
            new DetermineCustomerNode(),
            new DetermineMealNode(),
            new OrderIncompleteNode(),
            new OrderCompleteNode(),
        ];
    }

    private function isCustomerStateDone($state): bool
    {
        /** @customer Customer $customer */
        $customer = $state->get(self::CUSTOMER_OBJECT);
        if ($customer === null) {
            echo "[CustomerState unknown]" . PHP_EOL;
            return false;
        }

        if ($customer->getCustomerId() === 0) {
            echo "[CustomerState Customer ID is wrong]" . PHP_EOL;
            return false;
        }

        echo "[CustomerState is set with " . $customer->getCustomerId() . ']' . PHP_EOL;
        return true;
    }

    public function edges(): array
    {
        return [
            new Edge(
                DetermineCustomerNode::class, DetermineMealNode::class,
                function (WorkflowState $state): bool {
                    return $this->isCustomerStateDone($state);
                },
            ),

            new Edge(
                DetermineCustomerNode::class, OrderIncompleteNode::class,
                function (WorkflowState $state): bool {
                    return !$this->isCustomerStateDone($state);
                },
            ),

            new Edge(DetermineMealNode::class, OrderIncompleteNode::class),
        ];
    }

    protected function start(): string
    {
        return DetermineCustomerNode::class;
    }

    protected function end(): array
    {
        return [
            OrderIncompleteNode::class,
            OrderCompleteNode::class,
        ];
    }

}





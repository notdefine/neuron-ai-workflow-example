<?php

declare(strict_types=1);

namespace Notdefine\Workflow\Node;

use NeuronAI\Chat\Messages\UserMessage;
use NeuronAI\Workflow\Node;
use NeuronAI\Workflow\WorkflowState;
use Notdefine\Workflow\Agent\MealOrderAgent;
use Notdefine\Workflow\InspectorTrait;
use Notdefine\Workflow\StructuredOutput\MealStructure;
use Notdefine\Workflow\Workflow\OrderMealWorkflow;

class DetermineMealNode extends Node
{
    use InspectorTrait;

    public function __construct(
        private readonly MealOrderAgent $mealOrderAgentStructured,
        private readonly MealOrderAgent $mealOrderAgentChat,
    ) {}

    public function run(WorkflowState $state): WorkflowState
    {
        echo self::class . PHP_EOL;
        $mealOrderAgentStructured = $this->addAgentMonitoring($this->mealOrderAgentStructured);

        /** @var MealStructure $mealOrderAgentStructuredResponse */
        $mealOrderAgentStructuredResponse = $mealOrderAgentStructured->structured(
            new UserMessage($state->get('user_input')),
            MealStructure::class,
        );

        if ($mealOrderAgentStructuredResponse->isComplete()) {
            $state->set(OrderMealWorkflow::MEAL_OBJECT, $mealOrderAgentStructuredResponse);
            return $state;
        }

        $mealOrderAgentChat = $this->addAgentMonitoring($this->mealOrderAgentChat);

        $mealOrderAgentChatResponse = $mealOrderAgentChat->chat(
            new UserMessage($state->get('user_input')),
        );

        $state->set(OrderMealWorkflow::KI_RESPONSE, $mealOrderAgentChatResponse->getContent());

        return $state;
    }
}

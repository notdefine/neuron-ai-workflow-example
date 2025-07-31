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
        // Agents must be always the same instance to keep chat history
        private readonly MealOrderAgent $mealOrderAgentStructured,
        private readonly MealOrderAgent $mealOrderAgentChat,
    ) {}

    public function run(WorkflowState $state): WorkflowState
    {
        echo self::class . PHP_EOL;
        echo 'Message from user ' . $state->get('user_input') . PHP_EOL;

        /** @var MealStructure $mealOrderAgentStructuredResponse */
        $mealOrderAgentStructuredResponse = $this->mealOrderAgentStructured->structured(
            new UserMessage($state->get('user_input')),
            MealStructure::class,
        );

        if ($mealOrderAgentStructuredResponse->isComplete()) {
            echo '[Meal is complete!]' . PHP_EOL;
            $state->set(MealStructure::STATE_KEY, $mealOrderAgentStructuredResponse);
            return $state;
        }

        $mealOrderAgentChatResponse = $this->mealOrderAgentChat->chat(
            new UserMessage($state->get('user_input')),
        );

        $state->set(OrderMealWorkflow::KI_RESPONSE, $mealOrderAgentChatResponse->getContent());

        return $state;
    }
}

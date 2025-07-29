<?php

declare(strict_types=1);

namespace Notdefine\Workflow\Node;


use NeuronAI\Chat\Messages\UserMessage;
use NeuronAI\Workflow\Node;
use NeuronAI\Workflow\WorkflowState;
use Notdefine\Workflow\Agent\MealOrderAgent;
use Notdefine\Workflow\StructuredOutput\MealStructure;
use Notdefine\Workflow\Workflow\OrderMealWorkflow;

class DetermineMealNode extends Node
{
    public function run(WorkflowState $state): WorkflowState
    {
        echo self::class . PHP_EOL;
        /** @var MealStructure $meal */
        $meal = MealOrderAgent::make()->structured(
            new UserMessage($state->get('user_input')),
            MealStructure::class,
        );

        if ($meal->isComplete()) {
            $state->set(OrderMealWorkflow::MEAL_OBJECT, $meal);
            return $state;
        }

        $agentText = MealOrderAgent::make()->chat(
            new UserMessage($state->get('user_input')),
        );

        $state->set(OrderMealWorkflow::KI_RESPONSE, $agentText->getContent());

        return $state;
    }
}

<?php

declare(strict_types=1);

namespace Notdefine\Workflow\Node;

use NeuronAI\Workflow\Node;
use NeuronAI\Workflow\WorkflowState;
use Notdefine\Workflow\StructuredOutput\MealStructure;
use Notdefine\Workflow\Workflow\OrderMealWorkflow;

class OrderCompleteNode extends Node
{
    public function run(WorkflowState $state): WorkflowState
    {
        echo self::class . PHP_EOL;

        /** @var MealStructure $meal */
        $meal = $state->get(OrderMealWorkflow::MEAL_OBJECT);
        $state->set(
            OrderMealWorkflow::KI_RESPONSE,
            'Sending order to kitchen'
            . PHP_EOL . $meal->getName() . PHP_EOL . $meal->getDrink() . PHP_EOL . $meal->getEat(),
        );

        //        $feedback = $this->interrupt([
        //            'question' => 'Wait until meal is cooked!',
        //        ]);

        return $state;
    }
}

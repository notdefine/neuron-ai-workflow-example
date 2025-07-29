<?php

declare(strict_types=1);

namespace Notdefine\Workflow\Node;

use NeuronAI\Workflow\Node;
use NeuronAI\Workflow\WorkflowState;

class OrderCompleteNode extends Node
{
    public function run(WorkflowState $state): WorkflowState
    {
        echo self::class . PHP_EOL;
        echo "Sending order to kitchen" . PHP_EOL;


//        $feedback = $this->interrupt([
//            'question' => 'Wait until meal is cooked!',
//        ]);


        return $state;
    }
}
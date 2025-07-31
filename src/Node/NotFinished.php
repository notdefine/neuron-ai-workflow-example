<?php

declare(strict_types=1);

namespace Notdefine\Workflow\Node;

use NeuronAI\Workflow\Node;
use NeuronAI\Workflow\WorkflowState;

class NotFinished extends Node
{
    public function run(WorkflowState $state): WorkflowState
    {
        echo self::class . PHP_EOL;
        // $answers = array_map(fn($item) => $item['answer'], $state->get('answers'));
        // $state->set('data', $answers);

        return $state;
    }
}

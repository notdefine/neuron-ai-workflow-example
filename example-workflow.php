<?php

declare(strict_types=1);

namespace Notdefine\Workflow\Workflow;

use NeuronAI\Workflow\Persistence\FilePersistence;
use NeuronAI\Workflow\WorkflowState;

require_once __DIR__ . '/vendor/autoload.php';

if (empty(getenv('OPENAI_API_KEY'))) {
    exit('Please copy the .env.local to .env and set the OPENAI_API_KEY environment variable. Then restart the docker container.');
}

if (empty(getenv('INSPECTOR_API_KEY'))) {
    echo('No INSPECTOR_API_KEY provided, logging will be disabled.');
}

$state = new WorkflowState();

echo "Starting Workflow..." . PHP_EOL;

$workflow = new OrderMealWorkflow(
    new FilePersistence(__DIR__),
    'CUSTOM_ID',
);

$textInput = 'Hi';

while (!empty($textInput)) {
    $state->set('user_input', $textInput);

    $state = $workflow->run($state);
    echo 'Agent: ' . $state->get(OrderMealWorkflow::KI_RESPONSE) . PHP_EOL;
    $textInput = trim(fgets(STDIN));
}

dd($state->get(OrderMealWorkflow::MEAL_OBJECT));
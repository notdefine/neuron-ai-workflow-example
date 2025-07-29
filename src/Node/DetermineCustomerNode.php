<?php

declare(strict_types=1);

namespace Notdefine\Workflow\Node;


use NeuronAI\Chat\Messages\UserMessage;
use NeuronAI\Workflow\Node;
use NeuronAI\Workflow\WorkflowState;
use Notdefine\Workflow\Agent\GetCustomerAgent;
use Notdefine\Workflow\StructuredOutput\CustomerStructure;
use Notdefine\Workflow\Workflow\OrderMealWorkflow;

class DetermineCustomerNode extends Node
{
    public function run(WorkflowState $state): WorkflowState
    {
        echo self::class . PHP_EOL;

        if (!empty($state->get(OrderMealWorkflow::CUSTOMER_OBJECT))) {
            echo "[Customer already known]" . PHP_EOL;
            return $state;
        }

        /** @var CustomerStructure $customer */
        $customer = GetCustomerAgent::make()->structured(
            new UserMessage($state->get('user_input')),
            CustomerStructure::class,
        );

        $agentText = GetCustomerAgent::make()->chat(
            new UserMessage($state->get('user_input')),
        );

        if ($customer->getCustomerId() === 0) {
            echo "[Customer not known]" . PHP_EOL;
            $state->set(OrderMealWorkflow::KI_RESPONSE, $agentText->getContent());
            return $state;
        }

        echo "[Customer known]" . PHP_EOL;
        $state->set(OrderMealWorkflow::KI_RESPONSE, $agentText->getContent());
        $state->set(OrderMealWorkflow::CUSTOMER_OBJECT, $customer);

        return $state;
    }
}
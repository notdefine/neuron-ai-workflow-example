<?php

declare(strict_types=1);

namespace Notdefine\Workflow\Node;


use NeuronAI\Chat\Messages\UserMessage;
use NeuronAI\Workflow\Node;
use NeuronAI\Workflow\WorkflowState;
use Notdefine\Workflow\Agent\GetCustomerAgent;
use Notdefine\Workflow\InspectorTrait;
use Notdefine\Workflow\StructuredOutput\CustomerStructure;
use Notdefine\Workflow\Workflow\OrderMealWorkflow;

class DetermineCustomerNode extends Node
{
    use InspectorTrait;

    public function run(WorkflowState $state): WorkflowState
    {
        echo self::class . PHP_EOL;

        if (!empty($state->get(OrderMealWorkflow::CUSTOMER_OBJECT))) {
            echo "[Customer already known]" . PHP_EOL;
            return $state;
        }

        $customerAgentStructure = GetCustomerAgent::make();
        $customerAgentStructure = $this->addAgentMonitoring($customerAgentStructure);

        /** @var CustomerStructure $customerAgentStructureResponse */
        $customerAgentStructureResponse = $customerAgentStructure->structured(
            new UserMessage($state->get('user_input')),
            CustomerStructure::class,
        );

        $customerAgentText = GetCustomerAgent::make();
        $customerAgentText = $this->addAgentMonitoring($customerAgentText);

        $customerAgentChatResponse = $customerAgentText->chat(
            new UserMessage($state->get('user_input')),
        );

        if ($customerAgentStructureResponse->getCustomerId() === 0) {
            echo "[Customer not known]" . PHP_EOL;
            $state->set(OrderMealWorkflow::KI_RESPONSE, $customerAgentChatResponse->getContent());
            return $state;
        }

        echo "[Customer known]" . PHP_EOL;
        $state->set(OrderMealWorkflow::KI_RESPONSE, $customerAgentChatResponse->getContent());
        $state->set(OrderMealWorkflow::CUSTOMER_OBJECT, $customerAgentStructureResponse);

        return $state;
    }
}
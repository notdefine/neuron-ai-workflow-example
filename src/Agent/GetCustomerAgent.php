<?php

declare(strict_types=1);

namespace Notdefine\Workflow\Agent;

use NeuronAI\Agent;
use NeuronAI\Providers\AIProviderInterface;
use NeuronAI\Providers\OpenAI\OpenAI;
use NeuronAI\SystemPrompt;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\Tool;
use NeuronAI\Tools\ToolProperty;

class GetCustomerAgent extends Agent
{
    protected function provider(): AIProviderInterface
    {
        return new OpenAI(
            getenv('OPENAI_API_KEY') ?: null,
            'gpt-4.1',
        );
    }

    public function instructions(): string
    {
        $prompt = new SystemPrompt(
            background: ['You are an AI Agent specialized to get the User ID from the customers name. You must use get_user_id as a tool to get the User ID.'],
            steps: [
                'Ask for the customers name and find the User ID in the Database.',
            ],
            output: [
                'Greet the customer by name.',
            ],
        );

        return (string)$prompt;
    }

    protected function tools(): array
    {
        return [
            Tool::make(
                'get_user_id_by_customer_name',
                'Retrieve the User ID from the customers name from Database.',
            )
                ->addProperty(
                    new ToolProperty(
                        name: 'customerName',
                        type: PropertyType::from('string'),
                        description: 'The Customers Name.',
                        required: true,
                    ),
                )
                ->setCallable(
                // Pseudo implementation
                    function (string $customerName): int {
                        return strlen($customerName);
                    },
                ),
        ];
    }
}

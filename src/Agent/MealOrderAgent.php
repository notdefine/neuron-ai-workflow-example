<?php

declare(strict_types=1);

namespace Notdefine\Workflow\Agent;

use NeuronAI\Agent;
use NeuronAI\Providers\AIProviderInterface;
use NeuronAI\Providers\OpenAI\OpenAI;
use NeuronAI\SystemPrompt;

class MealOrderAgent extends Agent
{
    /*
     Das geht auch fluent - falls die settings aus zb. einer konfiguration kommen
      $agent = Agent::make()
    ->withAiProvider(
        new Anthropic(
            key: 'ANTHROPIC_API_KEY',
            model: 'ANTHROPIC_MODEL',
        )
    )
    ->withInstructions(
        new SystemPrompt(...)
    )
    ->addTool([...]);
$response = $agent->chat(new UserMessage(...));
     */
    protected function provider(): AIProviderInterface
    {
        //        return new Ollama(
        //            'https://ollama.mehrkanal.com/api',
        //            'qwen3:latest',
        //        );
        return new OpenAI(
            getenv('OPENAI_API_KEY') ?: null,
            'gpt-4.1',
        );
    }

    public function instructions(): string
    {
        $prompt = new SystemPrompt(
            background: ['You are an AI Agent specialized to take a meal order.'],
            steps: [
                'Get all details needed what the customer wants to eat and drink. Get the Customers name for the delivery.',
                'Suggest a matching meal and drink.',
                'Show a order summary. And let the customer approve the order.',
            ],
            output: [
                'Write a summary of the Order in a paragraph without using lists. Use just fluent text.',
            ],
        );

        return (string)$prompt;
    }
    //
    //    protected function tools(): array
    //    {
    //        return [
    //            Tool::make(
    //                'get_meals',
    //                'Retrieve the possible meals.',
    //            )
    //                ->addProperty(
    //                    new ToolProperty(
    //                        name: 'order_number',
    //                        type: PropertyType::from('string'),
    //                        description: 'The OrderNumber of the MMS Order.',
    //                        required: true,
    //                    ),
    //                )
    //                ->setCallable(
    //                    fn(): string => '
    //                        Teenagers / Adolescents (13–18 years)
    //    Middle school students
    //    High school students
    //
    //Young Adults (18–25 years)
    //
    //    College/university students
    //    Early career professionals
    //
    //Adults
    //
    //    Young professionals (25–35 years)
    //    Mid-career professionals (35–55 years)
    //    Older adults (55+ years)
    //
    //Seniors / Elderly (65+ years)
    //',
    //                ),
    //        ];
    //    }
}

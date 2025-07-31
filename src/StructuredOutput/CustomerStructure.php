<?php

declare(strict_types=1);

namespace Notdefine\Workflow\StructuredOutput;

use NeuronAI\StructuredOutput\SchemaProperty;

class CustomerStructure
{
    public const STATE_KEY = 'CustomerStructure';

    #[SchemaProperty(description: 'The customers name.', required: true)]
    public string $name;

    #[SchemaProperty(description: 'The customer id.', required: true)]
    public int $customerId = 0;

    public function getName(): string
    {
        return $this->name;
    }

    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    public function isComplete(): bool
    {
        if ($this->customerId === 0) {
            echo '[CustomerState Customer ID is wrong]' . PHP_EOL;
            return false;
        }

        echo '[CustomerState is set with ' . $this->customerId . ']' . PHP_EOL;
        return true;
    }
}

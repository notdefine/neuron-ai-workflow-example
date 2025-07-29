<?php

declare(strict_types=1);

namespace Notdefine\Workflow\StructuredOutput;

use NeuronAI\StructuredOutput\SchemaProperty;

class Customer
{
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
}
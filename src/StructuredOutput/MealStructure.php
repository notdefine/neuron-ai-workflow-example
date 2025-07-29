<?php

declare(strict_types=1);

namespace Notdefine\Workflow\StructuredOutput;

use NeuronAI\StructuredOutput\SchemaProperty;

class MealStructure
{
    #[SchemaProperty(description: 'The customers name.  If you dont know, leave it empty.', required: true)]
    public string $name;

    #[SchemaProperty(description: 'What the user love to eat. If you dont know, leave it empty.', required: false)]
    public string $eat;

    #[SchemaProperty(description: 'What the user love to drink. If you dont know, leave it empty.', required: true)]
    public string $drink;

    public function isComplete(): bool
    {
        return !empty($this->name) &&
            !empty($this->drink) &&
            !empty($this->eat);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEat(): string
    {
        return $this->eat;
    }

    public function getDrink(): string
    {
        return $this->drink;
    }
}
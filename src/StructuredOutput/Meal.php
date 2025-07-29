<?php

declare(strict_types=1);

namespace Notdefine\Workflow\StructuredOutput;

use NeuronAI\StructuredOutput\SchemaProperty;

class Meal
{
    #[SchemaProperty(description: 'The customers name.', required: true)]
    public string $name;

    #[SchemaProperty(description: 'What the user love to eat.', required: false)]
    public string $eat;

    #[SchemaProperty(description: 'What the user love to drink.', required: true)]
    public string $drink;

    public function isFinished(): bool
    {
        return !empty($this->name) && !empty($this->drink) && !empty($this->eat);
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
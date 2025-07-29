<?php

declare(strict_types=1);

namespace Notdefine\Workflow\Tools;

class GetOrder
{
    public function __invoke(string $order_number): string
    {
        // if order != this.user -> exception

        //TODO  Database lookup


        return 'Order ' . $order_number . PHP_EOL .
            'Shipping Address: Thomas Eimers, Berkenberg 33, 45309 Essen
        
        Position: Green Pullover 15$' . PHP_EOL .
            'Position: Red Pullover 22$';
    }
}

<?php

namespace App\Card;

use App\Card\Hand;

class HandEvaluator
{
    private array $rules;

    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    public function evaluate(Hand $hand): string
    {
        foreach ($this->rules as $rule) {
            if ($rule->matches($hand)) {
                return $rule->getName();
            }
        }

        return 'High Card';
    }
}

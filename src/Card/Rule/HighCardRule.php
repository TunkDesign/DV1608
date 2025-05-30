<?php

namespace App\Card\Rule;

use App\Card\Hand;
use App\Card\Rule\RuleInterface;

class HighCardRule implements RuleInterface
{
    public function matches(Hand $hand): bool
    {
        return true;
    }

    public function getName(): string
    {
        return 'High Card';
    }
}
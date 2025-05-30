<?php

namespace App\Card\Rule;

use App\Card\Hand;
use App\Card\Rule\RuleInterface;

class StraightRule implements RuleInterface
{
    public function matches(Hand $hand): bool
    {
        $values = [];
        foreach ($hand->getCards() as $card) {
            $values[] = $card->getValue();
        }

        sort($values);
        return max($values) - min($values) === 4 && count(array_unique($values)) === 5;
    }

    public function getName(): string
    {
        return 'Straight';
    }
}
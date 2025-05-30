<?php

namespace App\Card\Rule;

use App\Card\Hand;
use App\Card\Rule\RuleInterface;

class TwoPairRule implements RuleInterface
{
    public function matches(Hand $hand): bool
    {
        $counts = [];
        foreach ($hand->getCards() as $card) {
            $val = $card->getValue();
            $counts[$val] = ($counts[$val] ?? 0) + 1;
        }

        $pairs = 0;
        foreach ($counts as $count) {
            if ($count === 2) {
                $pairs++;
            }
        }

        return $pairs === 2;
    }

    public function getName(): string
    {
        return 'Two Pair';
    }
}
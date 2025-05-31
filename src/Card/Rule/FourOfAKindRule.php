<?php

namespace App\Card\Rule;

use App\Card\Hand;
use App\Card\Rule\RuleInterface;

class FourOfAKindRule implements RuleInterface
{
    public function matches(Hand $hand): bool
    {
        $counts = [];
        foreach ($hand->getCards() as $card) {
            $val = $card->getValue();
            $counts[$val] = ($counts[$val] ?? 0) + 1;
        }

        return in_array(4, $counts, true);
    }

    public function getName(): string
    {
        return 'Four of a Kind';
    }
}

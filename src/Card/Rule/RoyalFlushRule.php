<?php

namespace App\Card\Rule;

use App\Card\Hand;
use App\Card\Rule\RuleInterface;

class RoyalFlushRule implements RuleInterface
{
    public function matches(Hand $hand): bool
    {
        $cards = $hand->getCards();
        $values = [];
        $suits = [];

        foreach ($cards as $card) {
            $value = $card->getValue();
            if ($value === 1) {
                $value = 14;
            }
            $values[] = $value;
            $suits[] = $card->getSuit();
        }

        sort($values);
        $isFlush = count(array_unique($suits)) === 1;
        $isRoyal = $values === [10, 11, 12, 13, 14];

        return $isFlush && $isRoyal;
    }

    public function getName(): string
    {
        return 'Royal Flush';
    }
}

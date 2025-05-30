<?php

namespace App\Card\Rule;

use App\Card\Hand;
use App\Card\Rule\RuleInterface;

class StraightFlushRule implements RuleInterface
{
    public function matches(Hand $hand): bool
    {
        $cards = $hand->getCards();
        $suits = [];
        $values = [];

        foreach ($cards as $card) {
            $suits[] = $card->getSuit();
            $values[] = $card->getValue();
        }

        sort($values);
        $isFlush = count(array_unique($suits)) === 1;
        $isStraight = max($values) - min($values) === 4 && count(array_unique($values)) === 5;

        return $isFlush && $isStraight;
    }

    public function getName(): string
    {
        return 'Straight Flush';
    }
}
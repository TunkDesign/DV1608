<?php

namespace App\Card\Rule;

use App\Card\Hand;
use App\Card\Rule\RuleInterface;

class FlushRule implements RuleInterface
{
    public function matches(Hand $hand): bool
    {
        $suits = [];
        foreach ($hand->getCards() as $card) {
            $suits[] = $card->getSuit();
        }

        return count(array_unique($suits)) === 1;
    }

    public function getName(): string
    {
        return 'Flush';
    }
}

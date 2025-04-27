<?php

namespace App\Card;

class Hand
{
    private array $cards = [];

    public function addCard(CardGraphic $card): void
    {
        $this->cards[] = $card;
    }

    public function getCards(): array
    {
        return $this->cards;
    }

    public function getValue(): int
    {
        $sum = 0;

        foreach ($this->cards as $card) {
            $sum += $card->getValue();
        }

        return $sum;
    }
}

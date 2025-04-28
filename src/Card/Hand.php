<?php

namespace App\Card;

class Hand
{
    /**
     * @var list<CardGraphic>
     */
    private array $cards = [];

    /**
     * Add a card to the hand.
     *
     * @param CardGraphic $card
     */
    public function addCard(CardGraphic $card): void
    {
        $this->cards[] = $card;
    }

    /**
     * Get all cards in the hand.
     *
     * @return list<CardGraphic>
     */
    public function getCards(): array
    {
        return $this->cards;
    }

    /**
     * Calculate the total value of the hand.
     *
     * @return int
     */
    public function getValue(): int
    {
        $sum = 0;

        foreach ($this->cards as $card) {
            $sum += $card->getValue();
        }

        return $sum;
    }
}

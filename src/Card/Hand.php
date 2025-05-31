<?php

namespace App\Card;

class Hand
{
    /**
     * @var CardGraphic[]
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
     * Replace a card in the hand with a new card.
     * If the card to replace is not found, it will be added instead.
     *
     * @param int $index Index of the card to replace.
     * @param CardGraphic $newCard The new card to place in the hand.
     */
    public function replaceCard(int $index, CardGraphic $newCard): void
    {
        if (isset($this->cards[$index])) {
            $this->cards[$index] = $newCard;
        }
    }

    /**
     * Get all cards in the hand.
     *
     * @return CardGraphic[]
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

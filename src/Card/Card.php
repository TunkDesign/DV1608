<?php

namespace App\Card;

class Card
{
    /**
     * @var int The value of the card (typically 1-13).
     */
    protected int $value;

    /**
     * @var string The suit of the card (e.g., spades, hearts, diamonds, clubs).
     */
    protected string $suit;

    /**
     * Creates a new card with a given value and suit.
     *
     * @param int $value The value of the card (default is 1).
     * @param string $suit The suit of the card (default is 'spades').
     */
    public function __construct(int $value = 1, string $suit = 'spades')
    {
        $this->value = $value;
        $this->suit = $suit;
    }

    /**
     * Returns the value of the card.
     *
     * @return int The card's value.
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * Returns the suit of the card.
     *
     * @return string The card's suit.
     */
    public function getSuit(): string
    {
        return $this->suit;
    }

    /**
     * Returns a string representation of the card.
     *
     * @return string The card formatted as [suit value].
     */
    public function getAsString(): string
    {
        return "[{$this->suit} {$this->value}]";
    }
}

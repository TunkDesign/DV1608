<?php

namespace App\Card;

class Card
{
    protected int $value;
    protected string $suit;

    public function __construct(int $value = 1, string $suit = 'spades')
    {
        $this->value = $value;
        $this->suit = $suit;
    }
    
    public function getValue(): int
    {
        return $this->value;
    }

    public function getSuit(): string
    {
        return $this->suit;
    }

    public function getAsString(): string
    {
        return "[{$this->suit} {$this->value}]";
    }
}
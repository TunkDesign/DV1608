<?php

namespace App\Card;

class Player
{
    private string $name;
    private Hand $hand;
    private bool $folded = false;
    private bool $holding = false;
    private bool $active = false;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->hand = new Hand();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHand(): Hand
    {
        return $this->hand;
    }

    public function addCard(CardGraphic $card): void
    {
        $this->hand->addCard($card);
    }

    public function getScore(): int
    {
        return $this->hand->getValue();
    }

    public function fold(): void
    {
        $this->folded = true;
    }

    public function hasFolded(): bool
    {
        return $this->folded;
    }
    
    public function hold(): void
    {
        $this->holding = true;
    }
    
    public function isHolding(): bool
    {
        return $this->holding;
    }

    public function setActive(): void
    {
        $this->active = true;
    }

    public function setInactive(): void {
        $this->active = false;
    }

    public function isActive(): bool
    {
        return $this->active;
    }
}

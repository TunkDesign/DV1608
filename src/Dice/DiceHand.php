<?php

namespace App\Dice;

use App\Dice\Dice;

class DiceHand
{
    /**
     * @var Dice[] The dice contained in the hand.
     */
    private array $hand = [];

    /**
     * Adds a dice to the hand.
     *
     * @param Dice $die The dice to add.
     */
    public function add(Dice $die): void
    {
        $this->hand[] = $die;
    }

    /**
     * Rolls all dice in the hand.
     */
    public function roll(): void
    {
        foreach ($this->hand as $die) {
            $die->roll();
        }
    }

    /**
     * Returns the number of dice in the hand.
     *
     * @return int Number of dice.
     */
    public function getNumberDices(): int
    {
        return count($this->hand);
    }

    /**
     * Returns the numeric values of all dice in the hand.
     *
     * @return int[] An array of dice values.
     */
    public function getValues(): array
    {
        $values = [];
        foreach ($this->hand as $die) {
            $values[] = $die->getValue();
        }
        return $values;
    }

    /**
     * Returns the string representations of all dice in the hand.
     *
     * @return string[] An array of dice string representations.
     */
    public function getString(): array
    {
        $values = [];
        foreach ($this->hand as $die) {
            $values[] = $die->getAsString();
        }
        return $values;
    }
}

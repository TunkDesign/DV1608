<?php

namespace App\Dice;

class Dice
{
    /**
     * @var int|null The current value of the dice, or null if not yet rolled.
     */
    protected ?int $value;

    /**
     * Initializes a new dice without a rolled value.
     */
    public function __construct()
    {
        $this->value = null;
    }

    /**
     * Rolls the dice and returns the result.
     *
     * @return int The result of the roll (1-6).
     */
    public function roll(): int
    {
        $this->value = random_int(1, 6);
        return $this->value;
    }

    /**
     * Returns the current value of the dice.
     *
     * @return int The value of the dice, or 0 if not rolled yet.
     */
    public function getValue(): int
    {
        return $this->value ?? 0;
    }

    /**
     * Returns the dice value as a string.
     *
     * @return string The dice value in [x] format.
     */
    public function getAsString(): string
    {
        return "[{$this->value}]";
    }
}

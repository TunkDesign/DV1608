<?php

namespace App\Dice;

class DiceGraphic extends Dice
{
    /**
     * @var string[] Graphical representation for each dice value (1-6).
     */
    private array $representation = [
        '⚀',
        '⚁',
        '⚂',
        '⚃',
        '⚄',
        '⚅',
    ];

    /**
     * Initializes a new graphical dice.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Returns the dice value as a Unicode symbol.
     *
     * @return string The graphical representation of the dice value.
     */
    public function getAsString(): string
    {
        if ($this->value === null) {
            return '[ ]'; // In case the dice hasn't been rolled yet
        }

        return $this->representation[$this->value - 1];
    }
}

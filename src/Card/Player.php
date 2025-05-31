<?php

namespace App\Card;

use App\Card\HandEvaluator;
use App\Card\Rule\{
    RoyalFlushRule,
    StraightFlushRule,
    FourOfAKindRule,
    FullHouseRule,
    FlushRule,
    StraightRule,
    ThreeOfAKindRule,
    TwoPairRule,
    OnePairRule,
};

class Player
{
    /**
     * @var string The name of the player.
     */
    private string $name;

    /**
     * @var Hand The player's hand of cards.
     */
    private Hand $hand;

    /**
     * @var bool Whether the player has folded.
     */
    private bool $folded = false;

    /**
     * @var bool Whether the player is currently holding (standing).
     */
    private bool $holding = false;

    /**
     * @var bool Whether the player is currently active.
     */
    private bool $active = false;

    /**
     * @var int Current player balance.
     */
    private int $balance = 100;

    /**
     * @var int Current player bet.
     */
    private int $currentBet = 0;

    /**
     * Creates a new player with a given name.
     *
     * @param string $name The name of the player.
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->hand = new Hand();
    }

    /**
     * Returns the name of the player.
     *
     * @return string The player's name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the player's hand.
     *
     * @return Hand The player's hand of cards.
     */
    public function getHand(): Hand
    {
        return $this->hand;
    }

    public function getPlayHand(): string
    {
        $evaluator = new HandEvaluator([
            new RoyalFlushRule(),
            new StraightFlushRule(),
            new FourOfAKindRule(),
            new FullHouseRule(),
            new FlushRule(),
            new StraightRule(),
            new ThreeOfAKindRule(),
            new TwoPairRule(),
            new OnePairRule()
        ]);

        return $evaluator->evaluate($this->hand);
    }

    /**
     * Adds a card to the player's hand.
     *
     * @param CardGraphic $card The card to add.
     */
    public function addCard(CardGraphic $card): void
    {
        $this->hand->addCard($card);
    }

    /**
     * Returns the total score of the player's hand.
     *
     * @return int The sum of the card values.
     */
    public function getScore(): int
    {
        return $this->hand->getValue();
    }

    /**
     * Sets the player status to folded.
     */
    public function fold(): void
    {
        $this->folded = true;
    }

    /**
     * Checks if the player has folded.
     *
     * @return bool True if folded, false otherwise.
     */
    public function hasFolded(): bool
    {
        return $this->folded;
    }

    /**
     * Sets the player status to holding (standing).
     */
    public function hold(): void
    {
        $this->holding = true;
    }

    /**
     * Checks if the player is holding.
     *
     * @return bool True if holding, false otherwise.
     */
    public function isHolding(): bool
    {
        return $this->holding;
    }

    /**
     * Sets the player as active.
     */
    public function setActive(): void
    {
        $this->active = true;
    }

    /**
     * Sets the player as inactive.
     */
    public function setInactive(): void
    {
        $this->active = false;
    }

    /**
     * Checks if the player is currently active.
     *
     * @return bool True if active, false otherwise.
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * Get the player balance.
     *
     * @return int
     */
    public function getBalance(): int
    {
        return $this->balance;
    }

    /**
     * Set the player balance.
     *
     * @param int $money
     */
    public function addBalance($money): void
    {
        $this->balance += $money;
    }

    /**
     * Get the current bet amount.
     *
     * @return int
     */
    public function getBet(): int
    {
        return $this->currentBet;
    }

    /**
     * Place a bet for the player.
     *
     * @param int $amount The amount to bet.
     * @throws \Exception If the player does not have enough money.
     */
    public function bet(int $amount): void
    {
        if ($amount > $this->balance) {
            throw new \Exception("Player does not have enough money.");
        }

        $this->balance -= $amount;
        $this->currentBet = $amount;
    }

    /**
     * Reset the player's bet to zero.
     */
    public function resetBet(): void
    {
        $this->currentBet = 0;
    }

    /**
     * Make a move in the game.
     * This method should be overridden by subclasses to implement specific player behavior.
     *
     * @param Poker $game The poker game instance.
     * @param Player $player The player making the move.
     */
    public function makeMove(Poker $game, Player $player): void
    {
    }
}

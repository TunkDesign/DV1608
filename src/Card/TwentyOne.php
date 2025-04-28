<?php

namespace App\Card;

class TwentyOne
{
    private Deck $deck;

    /**
     * @var list<Player>
     */
    private array $players = [];

    private int $currentPlayerIndex = 0;
    private bool $endGame = false;

    public function __construct()
    {
        $this->deck = new Deck(true);
        $this->deck->shuffle();
    }

    /**
     * Set the current active player.
     *
     * @param Player $player
     */
    public function setPlayer(Player $player): void
    {
        foreach ($this->players as $index => $p) {
            if ($p === $player) {
                $this->currentPlayerIndex = $index;
                return;
            }
        }
    }

    /**
     * Get the current active player.
     *
     * @return Player
     */
    public function getPlayer(): Player
    {
        $player = $this->players[$this->currentPlayerIndex];
        $player->setActive();
        return $player;
    }

    /**
     * Get the last player in the players list.
     *
     * @return Player|null
     */
    public function getLastPlayer(): ?Player
    {
        $player = end($this->players);
        return $player instanceof Player ? $player : null;
    }

    /**
     * Move to the next player.
     */
    public function nextPlayer(): void
    {
        $prevPlayer = $this->getPlayer();
        $prevPlayer->setInactive();

        $this->currentPlayerIndex++;

        $currentPlayer = $this->getPlayer();
        $currentPlayer->setActive();

        if ($prevPlayer->hasFolded() && $currentPlayer === $this->getLastPlayer()) {
            $this->endGame();
        }
    }

    /**
     * Get the deck.
     *
     * @return Deck
     */
    public function getDeck(): Deck
    {
        return $this->deck;
    }

    /**
     * Add a new player.
     *
     * @param Player $player
     */
    public function addPlayer(Player $player): void
    {
        $this->players[] = $player;
    }

    /**
     * Get all players.
     *
     * @return list<Player>
     */
    public function getPlayers(): array
    {
        return $this->players;
    }

    /**
     * Draw a card for a player.
     *
     * @param Player $player
     * @return CardGraphic|false
     */
    public function draw(Player $player): CardGraphic|false
    {
        if (!$player->isHolding() && !$player->hasFolded()) {
            $drawn = $this->deck->draw();

            if ($drawn instanceof CardGraphic) {
                $player->addCard($drawn);

                if ($this->calculateScore($player) > 21) {
                    $player->fold();
                    if ($player === $this->getLastPlayer()) {
                        $this->endGame();
                    } else {
                        $this->nextPlayer();
                    }
                } else {
                    return $drawn;
                }
            }
        }
        return false;
    }

    /**
     * End the game.
     */
    public function endGame(): void
    {
        $this->endGame = true;
    }

    /**
     * Check if the game has ended.
     *
     * @return bool
     */
    public function hasEnded(): bool
    {
        return $this->endGame;
    }

    /**
     * Calculate the score for a player.
     *
     * @param Player $player
     * @return int
     */
    private function calculateScore(Player $player): int
    {
        $sum = 0;
        $cards = $player->getHand()->getCards();

        foreach ($cards as $card) {
            $sum += $card->getValue();
        }

        return $sum;
    }

    /**
     * Get the winner of the game.
     *
     * @return Player|null
     */
    public function getWinner(): ?Player
    {
        if (!$this->endGame) {
            return null;
        }

        $activePlayers = [];
        $winner = null;
        $highestScore = 0;
        $draw = false;

        foreach ($this->players as $player) {
            if (!$player->hasFolded()) {
                $activePlayers[] = $player;
            }

            $player->setInactive();
        }

        if (count($activePlayers) === 0) {
            return null;
        }

        foreach ($activePlayers as $player) {
            $score = $this->calculateScore($player);

            if ($score > $highestScore) {
                $highestScore = $score;
                $winner = $player;
                $draw = false;
            } elseif ($score === $highestScore) {
                $draw = true;
            }
        }

        if ($draw) {
            return $this->getLastPlayer();
        }

        return $winner;
    }
}

<?php

namespace App\Card;

class TwentyOne
{
    private Deck $deck;
    private array $players = [];
    private int $currentPlayerIndex = 0;
    private bool $endGame = false;

    public function __construct()
    {
        $this->deck = new Deck(true);
        $this->deck->shuffle();
    }

    public function setPlayer(Player $player): void
    {
        foreach ($this->players as $index => $p) {
            if ($p === $player) {
                $this->currentPlayerIndex = $index;
                return;
            }
        }
    }

    public function getPlayer(): Player
    {
        $this->players[$this->currentPlayerIndex]->setActive();
        return $this->players[$this->currentPlayerIndex];
    }

    public function getLastPlayer(): Player
    {
        return end($this->players);
    }

    public function nextPlayer(): void
    {
        $prevPlayer = $this->getPlayer();
        $prevPlayer->setInactive();

        $this->currentPlayerIndex++;
        
        $this->getPlayer()->setActive();

        if ($prevPlayer->hasFolded() && $this->getPlayer() === $this->getLastPlayer()) {
            $this->endGame();
        }
    }

    public function getDeck(): Deck
    {
        return $this->deck;
    }

    public function addPlayer(Player $player): void
    {
        $this->players[] = $player;
    }

    public function getPlayers(): array
    {
        return $this->players;
    }

    public function draw(Player $player): mixed
    {
        if (!$player->isHolding()) {
            if (!$player->hasFolded()) {
                $drawn = $this->deck->draw();

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

    public function endGame(): void
    {
        $this->endGame = true;
    }

    public function hasEnded(): bool
    {
        return $this->endGame;
    }

    private function calculateScore(Player $player): int
    {
        $sum = 0;
        $cards = $player->getHand()->getCards();

        foreach ($cards as $card) {
            $value = $card->getValue();
            if ($card->getValue() === 1) {
                $sum += ($sum >= 7) ? 1 : 14;
            } else {
                $sum += $value;
            }
        }

        return $sum;
    }

    public function getWinner(): mixed
    {
        if ($this->endGame) {
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
                } elseif ($score === $highestScore) {
                    $draw = true;
                }
            }

            if ($draw) {
                return $this->getLastPlayer();
            }

            return $winner;
        }
    
        return null;
    }
}

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
    HighCardRule
};

class Poker
{
    private Deck $deck;
    private array $players = [];
    private int $currentIndex = 0;
    private bool $endGame = false;
    private int $pot = 0;
    private string $stage = 'deal';

    public function __construct()
    {
        $this->deck = new Deck(true);
        $this->deck->shuffle();
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
     * @return Player[]
     */
    public function getPlayers(): array
    {
        return $this->players;
    }

    /**
     * Get the current active player.
     * 
     * @return Player
     */
    public function getPlayer(): Player
    {
        return $this->players[$this->currentIndex];
    }

    /**
     * Get the index of the current active player.
     * 
     * @return int
     */
    public function getPlayerIndex(): int
    {
        return $this->currentIndex;
    }

    public function nextPlayer(): void
    {
        $this->currentIndex = ($this->currentIndex + 1) % count($this->players);
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

                return $drawn;
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
     */
    public function hasEnded(): bool
    {
        return $this->endGame;
    }

    /**
     * Redraw cards for a player.
     * @param Player $player
     * @param array<int, CardGraphic> $cards Cards to redraw.
     * @return array<int, CardGraphic> Returns the drawn cards.
     */
    public function redraw(Player $player, $cards): array
    {
        //$playercards = $player->getHand()->getCards();
        $drawnCards = [];

        foreach ($cards as $card) {
            $drawn = $this->deck->draw();

            if ($drawn instanceof CardGraphic) {
                $player->getHand()->replaceCard($card, $drawn);
                $drawnCards[] = $drawn;
            }
        }

        return $drawnCards;
    }


    /**
     * Evaluate a player's hand and return the rank.
     * 
     * @param Player $player
     * @return string
     */
    public function evaluateHand(Player $player): string
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
            new OnePairRule(),
            new HighCardRule(),
        ]);

        return $evaluator->evaluate($player->getHand());
    }

    public function getWinner(): Player
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
            new OnePairRule(),
            new HighCardRule(),
        ]);

        $ranking = [
            'High Card' => 1,
            'One Pair' => 2,
            'Two Pair' => 3,
            'Three of a Kind' => 4,
            'Straight' => 5,
            'Flush' => 6,
            'Full House' => 7,
            'Four of a Kind' => 8,
            'Straight Flush' => 9,
            'Royal Flush' => 10
        ];

        $bestPlayer = null;
        $bestRank = 0;
        $bestScore = 0;

        foreach ($this->players as $player) {
            $hand = $player->getHand();
            $handName = $evaluator->evaluate($hand);
            $rank = $ranking[$handName];
            $score = $player->getScore();

            if ($rank > $bestRank) {
                $bestRank = $rank;
                $bestPlayer = $player;
                $bestScore = $score;
            } elseif ($rank === $bestRank && $score > $bestScore) {
                $bestPlayer = $player;
                $bestScore = $score;
            }
        }

        $bestPlayer->addBalance($this->pot);
        $this->pot = 0;

        return $bestPlayer;
    }

    public function addToPot(int $amount): void
    {
        $this->pot += $amount;
    }

    public function getPot(): int
    {
        return $this->pot;
    }

    public function placeBet(Player $player, int $amount): void
    {
        $player->bet($amount);
        $this->addToPot($amount);
    }

    public function runComputerBets(): array
    {
        $bets = [];
        foreach ($this->players as $player) {
            if ($player instanceof MonkeyPlayer) {
                
                $amount = 10;
                $this->placeBet($player, $amount);
                $bets[] = ['name' => $player->getName(), 'amount' => $amount];
            }
            if ($player instanceof ComputerPlayer) {
                
                $hand = $player->getHand()->getCards();
                $valueCounts = [];
                foreach ($hand as $index => $card) {
                    $value = $card->getValue();
                    if (!isset($valueCounts[$value])) {
                        $valueCounts[$value] = [];
                    }
                    $valueCounts[$value][] = $index;
                }
                $maxCount = 1;
                foreach ($valueCounts as $indexes) {
                    if (count($indexes) > $maxCount) {
                        $maxCount = count($indexes);
                    }
                }
                $amount = 10;
                if ($maxCount == 2) {
                    $amount = 30;
                } elseif ($maxCount == 3) {
                    $amount = 60;
                } elseif ($maxCount >= 4) {
                    $amount = 100;
                }
                $this->placeBet($player, $amount);
                $bets[] = ['name' => $player->getName(), 'amount' => $amount];
            }
        }
        return $bets;
    }

    public function getStage(): string
    {
        return $this->stage;
    }

    public function setStage(string $stage): void
    {
        $this->stage = $stage;
    }

    public function fullHands(): bool
    {
        foreach ($this->players as $player) {
            if (count($player->getHand()->getCards()) < 5) {
                return false;
            }
        }
        return true;
    }
}

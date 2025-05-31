<?php

namespace App\Card;

use App\Card\Player;
use App\Card\Poker;

/**
 * Represents a computer-controlled player in a poker game.
 * The computer evaluates its hand and chooses cards to redraw based on matching values.
 */
class ComputerPlayer extends Player
{
    /**
     * Executes the computer's move in the poker game.
     * 
     * The strategy is to keep all cards that share the same value with at least one other card
     * (e.g. pairs, three-of-a-kind) and redraw up to three other cards.
     *
     * @param Poker  $game   The current poker game instance.
     * @param Player $player The player whose hand is being evaluated (typically itself).
     *
     * @return void
     */
    public function makeMove(Poker $game, Player $player): void
    {
        $hand = $player->getHand()->getCards();

        $valueCounts = array();
        foreach ($hand as $index => $card) {
            $value = $card->getValue();
            if (!isset($valueCounts[$value])) {
                $valueCounts[$value] = array();
            }
            $valueCounts[$value][] = $index;
        }

        $keep = array();
        foreach ($valueCounts as $indexes) {
            if (count($indexes) >= 2) {
                $keep = array_merge($keep, $indexes);
            }
        }

        $allIndexes = range(0, count($hand) - 1);
        $changeIndexes = array_diff($allIndexes, $keep);
        $changeIndexes = array_slice(array_values($changeIndexes), 0, 3);

        $cardsToChange = [];
        foreach ($changeIndexes as $index) {
            $cardsToChange[] = $hand[$index];
        }

        if (!empty($cardsToChange)) {
            $game->redraw($player, $cardsToChange);
        }
    }
}

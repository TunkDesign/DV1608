<?php

namespace App\Card;

use App\Card\Player;
use App\Card\Poker;

class ComputerPlayer extends Player
{
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
        $change = array_diff($allIndexes, $keep);
        $change = array_slice(array_values($change), 0, 3);

        if (!empty($change)) {
            $game->redraw($player, $change);
        }
    }
}

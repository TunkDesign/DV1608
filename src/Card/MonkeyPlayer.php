<?php

namespace App\Card;

use App\Card\Player;
use App\Card\Poker;

class MonkeyPlayer extends Player
{
    public function makeMove(Poker $game, Player $player): void
    {
        $cardIndexes = [
            [0, 1, 2],
            [0, 2, 1],
            [1, 0, 2],
            [1, 2, 0],
            [2, 0, 1],
            [2, 1, 0],
        ];
        $randomCards = $cardIndexes[array_rand($cardIndexes)];

        $game->redraw($player, $randomCards);
    }
}

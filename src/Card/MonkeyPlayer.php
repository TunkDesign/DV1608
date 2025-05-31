<?php

namespace App\Card;

use App\Card\Player;
use App\Card\Poker;
use App\Card\CardGraphic;

/**
 * Represents a monkey player in a poker game.
 * 
 * The monkey selects a random permutation of the first three cards in its hand
 * and requests to redraw those cards, mimicking a random or "chaotic" strategy.
 */
class MonkeyPlayer extends Player
{
    /**
     * Executes the monkey's move in the poker game.
     * 
     * Randomly selects a permutation of the first three card indexes and redraws
     * the corresponding cards from the hand.
     *
     * @param Poker  $game   The current poker game instance.
     * @param Player $player The player whose move is being made (typically itself).
     *
     * @return void
     */
    public function makeMove(Poker $game, Player $player): void
    {
        $cardIndexes = [
            [0, 1, 2],
            [0, 1, 3],
            [0, 1, 4],
            [0, 2, 3],
            [0, 2, 4],
            [0, 3, 4],
            [1, 2, 3],
            [1, 2, 4],
            [1, 3, 4],
            [2, 3, 4],
        ];
        $randomIndexes = $cardIndexes[array_rand($cardIndexes)];

        $hand = $player->getHand()->getCards();
        $cardsToRedraw = [];

        foreach ($randomIndexes as $index) {
            if (isset($hand[$index])) {
                $cardsToRedraw[] = $hand[$index];
            }
        }

        if (!empty($cardsToRedraw)) {
            $game->redraw($player, $cardsToRedraw);
        }
    }
}

<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\MonkeyPlayer;
use App\Card\Poker;

/**
 * Test cases for class MonkeyPlayer.
 */
class MonkeyPlayerTest extends TestCase
{
    public function testMakeMove(): void
    {
        $game = new Poker();

        $game->addPlayer(new MonkeyPlayer('Monkey'));

        $player = $game->getPlayer();
        $this->assertInstanceOf(MonkeyPlayer::class, $player);
        
        $game->draw($player);
        $game->draw($player);
        $game->draw($player);
        $game->draw($player);
        $game->draw($player);

        $currentHand = $player->getHand()->getCards();
        
        $this->assertCount(5, $currentHand);

        $player->makeMove($game, $player);
        
        $newHand = $player->getHand()->getCards();

        $this->assertNotSame($currentHand, $newHand);
    }
}

<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\TwentyOne;
use App\Card\Player;
use App\Card\CardGraphic;

/**
 * Test cases for class TwentyOne.
 */
class TwentyOneTest extends TestCase
{

    public function testLastPlayerEndsGame(): void
    {
        $game = new TwentyOne();
        $player1 = new Player("Ewa");
        $player2 = new Player("Karl");

        $game->addPlayer($player1);
        $game->addPlayer($player2);

        $player1->fold();

        $game->nextPlayer();

        $this->assertTrue($game->hasEnded());
    }

    public function testPlayerDrawCardUntilFolded(): void 
    {
        $game = new TwentyOne();
        $player1 = new Player("Ewa");
        $player2 = new Player("Karl");
        $player3 = new Player("Julia");

        $game->addPlayer($player1);
        $game->addPlayer($player2);
        $game->addPlayer($player3);

        $oldPlayer = $game->getPlayer();

        while (!$player1->hasFolded()) {
            $game->draw($player1);
        }

        while (!$player3->hasFolded()) {
            $game->draw($player3);
        }

        $newPlayer = $game->getPlayer();

        $this->assertTrue($player1->hasFolded());
        $this->assertNotSame($oldPlayer, $newPlayer);
        $this->assertTrue($game->hasEnded());
    }

    public function testGetDeck(): void
    {
        $game = new TwentyOne();
        $this->assertIsNotArray($game->getDeck());
    }

    public function testAddAndGetPlayers(): void
    {
        $game = new TwentyOne();
        $player1 = new Player("Ewa");
        $player2 = new Player("Karl");

        $game->addPlayer($player1);
        $game->addPlayer($player2);

        $players = $game->getPlayers();

        $this->assertCount(2, $players);
        $this->assertSame($player1, $players[0]);
        $this->assertSame($player2, $players[1]);
    }

    public function testSetAndGetCurrentPlayer(): void
    {
        $game = new TwentyOne();
        $player1 = new Player("Ewa");
        $player2 = new Player("Karl");

        $game->addPlayer($player1);
        $game->addPlayer($player2);
        $game->setPlayer($player2);

        $current = $game->getPlayer();
        $this->assertSame($player2, $current);
        $this->assertTrue($current->isActive());
    }

    public function testGetLastPlayer(): void
    {
        $game = new TwentyOne();
        $player1 = new Player("Ewa");
        $player2 = new Player("Karl");

        $game->addPlayer($player1);
        $game->addPlayer($player2);

        $this->assertSame($player2, $game->getLastPlayer());
    }

    public function testNextPlayerSwitchesActivePlayer(): void
    {
        $game = new TwentyOne();
        $player1 = new Player("Ewa");
        $player2 = new Player("Karl");

        $game->addPlayer($player1);
        $game->addPlayer($player2);

        $game->setPlayer($player1);
        $game->nextPlayer();

        $this->assertFalse($player1->isActive());
        $this->assertTrue($player2->isActive());
    }

    public function testDrawAddsCardToPlayer(): void
    {
        $game = new TwentyOne();
        $player = new Player("Ewa");

        $game->addPlayer($player);
        $drawn = $game->draw($player);

        $this->assertInstanceOf(CardGraphic::class, $drawn);
        $this->assertCount(1, $player->getHand()->getCards());
    }

    public function testDrawWithHoldingOrFoldedReturnsFalse(): void
    {
        $game = new TwentyOne();
        $player = new Player("Ewa");
        $player->hold();

        $game->addPlayer($player);
        $this->assertFalse($game->draw($player));

        $player = new Player("Karl");
        $player->fold();
        $game->addPlayer($player);
        $this->assertFalse($game->draw($player));
    }

    public function testEndGameSetsFlag(): void
    {
        $game = new TwentyOne();
        $this->assertFalse($game->hasEnded());

        $game->endGame();
        $this->assertTrue($game->hasEnded());
    }

    public function testGetWinnerReturnsNullBeforeGameEnds(): void
    {
        $game = new TwentyOne();
        $this->assertNull($game->getWinner());
    }

    public function testGetWinnerReturnsHighestScorePlayer(): void
    {
        $game = new TwentyOne();
        $p1 = new Player("Karl");
        $p2 = new Player("Ewa");

        $p1->addCard(new CardGraphic(10, "hearts"));
        $p1->addCard(new CardGraphic(9, "spades"));

        $p2->addCard(new CardGraphic(10, "diamonds"));
        $p2->addCard(new CardGraphic(5, "clubs"));

        $game->addPlayer($p1);
        $game->addPlayer($p2);

        $game->endGame();

        $winner = $game->getWinner();
        $this->assertSame($p1, $winner);
    }

    public function testGetWinnerReturnsLastPlayerOnDraw(): void
    {
        $game = new TwentyOne();
        $p1 = new Player("Karl");
        $p2 = new Player("Ewa");

        $p1->addCard(new CardGraphic(10, "hearts"));
        $p2->addCard(new CardGraphic(10, "spades"));

        $game->addPlayer($p1);
        $game->addPlayer($p2);

        $game->endGame();

        $winner = $game->getWinner();
        $this->assertSame($p2, $winner);
    }

    public function testGetWinnerReturnsNullIfAllFolded(): void
    {
        $game = new TwentyOne();
        $p1 = new Player("Karl");
        $p2 = new Player("Ewa");

        $p1->fold();
        $p2->fold();

        $game->addPlayer($p1);
        $game->addPlayer($p2);
        $game->endGame();

        $this->assertNull($game->getWinner());
    }
}

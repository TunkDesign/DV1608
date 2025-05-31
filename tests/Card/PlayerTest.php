<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\Player;
use App\Card\CardGraphic;
use App\Card\Hand;
use App\Card\Poker;

/**
 * Test cases for class Player.
 */
class PlayerTest extends TestCase
{
    public function testConstructorSetsNameAndCreatesHand(): void
    {
        $player = new Player("Karl");

        $this->assertEquals("Karl", $player->getName());
        $this->assertInstanceOf(Hand::class, $player->getHand());
    }

    public function testAddCardAddsCardToPlayersHand(): void
    {
        $player = new Player("Ewa");
        $card = new CardGraphic(4, "diamonds");

        $player->addCard($card);
        $cards = $player->getHand()->getCards();

        $this->assertCount(1, $cards);
        $this->assertSame($card, $cards[0]);
    }

    public function testGetScoreReturnsSumOfCardValues(): void
    {
        $player = new Player("Ewa");

        $player->addCard(new CardGraphic(5, "hearts"));
        $player->addCard(new CardGraphic(7, "clubs"));

        $this->assertEquals(12, $player->getScore());
    }

    public function testFoldSetsFoldedTrue(): void
    {
        $player = new Player("Ewa");
        $this->assertFalse($player->hasFolded());

        $player->fold();
        $this->assertTrue($player->hasFolded());
    }

    public function testHoldSetsHoldingTrue(): void
    {
        $player = new Player("Ewa");
        $this->assertFalse($player->isHolding());

        $player->hold();
        $this->assertTrue($player->isHolding());
    }

    public function testSetActiveAndInactiveTogglesStatus(): void
    {
        $player = new Player("Ewa");

        $this->assertFalse($player->isActive());
        $player->setActive();
        $this->assertTrue($player->isActive());

        $player->setInactive();
        $this->assertFalse($player->isActive());
    }

    public function testGetPlayHand(): void
    {
        $game = new Poker();

        $game->addPlayer(new Player('Ewa'));

        $playerEwa = $game->getPlayers()[0];

        $playerEwa->addCard(new CardGraphic(2));
        $playerEwa->addCard(new CardGraphic(2));
        $playerEwa->addCard(new CardGraphic(4));
        $playerEwa->addCard(new CardGraphic(4));
        $playerEwa->addCard(new CardGraphic(4));

        $this->assertEquals($playerEwa->getPlayHand(), 'Full House');
    }

    public function testGetBalance(): void
    {
        $game = new Poker();

        $game->addPlayer(new Player('Ewa'));

        $this->assertEquals(100, $game->getPlayer()->getBalance());
    }

    public function testBetting(): void
    {
        $game = new Poker();

        $game->addPlayer(new Player('Ewa'));

        try {
            $game->getPlayer()->bet(200);
        } catch (\Throwable $th) {
            $this->assertStringContainsString('Player does not have enough money.', $th);
        }

        $game->getPlayer()->bet(10);

        $this->assertEquals(90, $game->getPlayer()->getBalance());
    }

    public function testResetBet(): void
    {
        $game = new Poker();

        $game->addPlayer(new Player('Ewa'));
        $game->getPlayer()->resetBet();

        $this->assertEquals(0, $game->getPlayer()->getBet());
    }

    public function testMakeMoveDoesNothingByDefault(): void
    {
        $game = new Poker();
        $player = new Player('Test');

        $this->expectNotToPerformAssertions();

        $player->makeMove($game, $player);
    }
}

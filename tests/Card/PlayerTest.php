<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\Player;
use App\Card\CardGraphic;
use App\Card\Hand;

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
}

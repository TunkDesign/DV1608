<?php

namespace App\Tests\Card;

use App\Card\CardGraphic;
use PHPUnit\Framework\TestCase;
use App\Card\ComputerPlayer;
use App\Card\Poker;

/**
 * Test cases for class ComputerPlayer.
 */
class ComputerPlayerTest extends TestCase
{
    public function testMakeMove(): void
    {
        $game = new Poker();
        $player = new ComputerPlayer('Computer');
        $game->addPlayer($player);

        $player->addCard(new CardGraphic(8, 'hearts'));
        $player->addCard(new CardGraphic(8, 'clubs'));
        $player->addCard(new CardGraphic(4, 'spades'));
        $player->addCard(new CardGraphic(9, 'diamonds'));
        $player->addCard(new CardGraphic(13, 'spades'));

        $initialHand = $player->getHand()->getCards();
        $this->assertCount(5, $initialHand);

        $initialValues = array();
        foreach ($initialHand as $card) {
            $initialValues[] = $card->getValue();
        }

        $counts = array();
        foreach ($initialValues as $value) {
            if (!isset($counts[$value])) {
                $counts[$value] = 0;
            }
            $counts[$value]++;
        }

        $keepIndexes = array();
        foreach ($initialHand as $index => $card) {
            if ($counts[$card->getValue()] >= 2) {
                $keepIndexes[] = $index;
            }
        }

        $player->makeMove($game, $player);

        $newHand = $player->getHand()->getCards();
        $this->assertCount(5, $newHand);

        foreach ($keepIndexes as $index) {
            $expectedValue = $initialHand[$index]->getValue();
            $actualValue = $newHand[$index]->getValue();

            $this->assertEquals(
                $expectedValue,
                $actualValue
            );
        }
    }
}

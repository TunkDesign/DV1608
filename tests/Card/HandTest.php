<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\Hand;
use App\Card\CardGraphic;

/**
 * Test cases for class Hand.
 */
class HandTest extends TestCase
{
    public function testAddCardStoresCardInHand(): void
    {
        $hand = new Hand();
        $card = new CardGraphic(7, "hearts");

        $hand->addCard($card);
        $cards = $hand->getCards();

        $this->assertCount(1, $cards);
        $this->assertSame($card, $cards[0]);
    }

    public function testGetCardsReturnsAllCards(): void
    {
        $hand = new Hand();
        $card1 = new CardGraphic(3, "diamonds");
        $card2 = new CardGraphic(11, "spades");

        $hand->addCard($card1);
        $hand->addCard($card2);

        $cards = $hand->getCards();

        $this->assertCount(2, $cards);
        $this->assertSame($card1, $cards[0]);
        $this->assertSame($card2, $cards[1]);
    }

    public function testGetValueReturnsTotalSum(): void
    {
        $hand = new Hand();
        $hand->addCard(new CardGraphic(5, "clubs"));
        $hand->addCard(new CardGraphic(9, "hearts"));

        $value = $hand->getValue();

        $this->assertEquals(14, $value);
    }

    public function testGetValueIsZeroWhenEmpty(): void
    {
        $hand = new Hand();
        $this->assertEquals(0, $hand->getValue());
    }
}

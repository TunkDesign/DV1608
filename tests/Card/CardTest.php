<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\Card;

/**
 * Test cases for class Card.
 */
class CardTest extends TestCase
{
    public function testCreateCard(): void
    {
        $card = new Card(10, "hearts");
        $this->assertInstanceOf(Card::class, $card);
    }

    public function testGetSuit(): void
    {
        $card = new Card(5, "spades");
        $this->assertEquals("spades", $card->getSuit());
    }

    public function testGetValue(): void
    {
        $card = new Card(7, "clubs");
        $this->assertEquals(7, $card->getValue());
    }

    public function testGetAsString(): void
    {
        $card = new Card(13, "diamonds");
        $this->assertEquals("[diamonds 13]", $card->getAsString());
    }
}

<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\Deck;
use App\Card\Card;
use App\Card\CardGraphic;

/**
 * Test cases for class Deck.
 */
class DeckTest extends TestCase
{
    public function testDefaultDeckHas52Cards(): void
    {
        $deck = new Deck();
        $cards = $deck->getCards();

        $this->assertCount(52, $cards);
        $this->assertInstanceOf(Card::class, $cards[0]);
    }

    public function testGraphicDeckHasCardGraphicObjects(): void
    {
        $deck = new Deck(true);
        $cards = $deck->getCards();

        $this->assertCount(52, $cards);
        $this->assertInstanceOf(CardGraphic::class, $cards[0]);
    }

    public function testPreloadedDeckUsesExactCards(): void
    {
        $preloaded = [
            ['value' => 1, 'suit' => 'hearts'],
            ['value' => 13, 'suit' => 'spades']
        ];

        $deck = new Deck(true, $preloaded);
        $cards = $deck->getCards();

        $this->assertCount(2, $cards);
        $this->assertEquals('hearts', $cards[0]->getSuit());
        $this->assertEquals(1, $cards[0]->getValue());
        $this->assertEquals('spades', $cards[1]->getSuit());
        $this->assertEquals(13, $cards[1]->getValue());
    }

    public function testGetCardsBySuitReturnsOnlyThatSuit(): void
    {
        $deck = new Deck();
        $spades = $deck->getCardsBySuit('spades');

        $this->assertCount(13, $spades);

        foreach ($spades as $card) {
            $this->assertEquals('spades', $card->getSuit());
        }
    }

    public function testSortedCardsBySuitAreSortedByValue(): void
    {
        $deck = new Deck();
        $sorted = $deck->getSortedCardsBySuit('hearts');

        $this->assertCount(13, $sorted);

        for ($i = 0; $i < 13; $i++) {
            $this->assertEquals($i + 1, $sorted[$i]->getValue());
        }
    }

    public function testShuffleChangesOrder(): void
    {
        $deck1 = new Deck();
        $deck2 = new Deck();

        $deck2->shuffle();

        $cards1 = $deck1->getCards();
        $cards2 = $deck2->getCards();

        $identical = true;
        for ($i = 0; $i < 52; $i++) {
            if ($cards1[$i]->getValue() !== $cards2[$i]->getValue() ||
                $cards1[$i]->getSuit() !== $cards2[$i]->getSuit()) {
                $identical = false;
                break;
            }
        }

        $this->assertFalse($identical);
    }

    public function testDrawRemovesOneCard(): void
    {
        $deck = new Deck();
        $initial = count($deck->getCards());

        $card = $deck->draw();

        $this->assertInstanceOf(Card::class, $card);
        $this->assertCount($initial - 1, $deck->getCards());
    }

    public function testDrawReturnsNullWhenEmpty(): void
    {
        $deck = new Deck();

        for ($i = 0; $i < 52; $i++) {
            $deck->draw();
        }

        $this->assertNull($deck->draw());
    }
}

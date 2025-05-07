<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\CardGraphic;

/**
 * Test cases for class CardGraphic.
 */
class CardGraphicTest extends TestCase
{
    public function testGetAsStringReturnsCorrectUnicodeSymbol(): void
    {
        $card = new CardGraphic(1, 'spades');
        $this->assertEquals("🂡", $card->getAsString());

        $card = new CardGraphic(13, 'hearts');
        $this->assertEquals("🂾", $card->getAsString());

        $card = new CardGraphic(5, 'diamonds');
        $this->assertEquals("🃅", $card->getAsString());

        $card = new CardGraphic(12, 'clubs');
        $this->assertEquals("🃝", $card->getAsString());
    }

    public function testGetAsStringHandlesInvalidCardGracefully(): void
    {
        $card = new CardGraphic(14, 'spades');
        $this->assertEquals("[Invalid card]", $card->getAsString());

        $card = new CardGraphic(1, 'nonsense');
        $this->assertEquals("[Invalid card]", $card->getAsString());
    }

    public function testGetColorReturnsCorrectColor(): void
    {
        $this->assertEquals('red', (new CardGraphic(1, 'hearts'))->getColor());
        $this->assertEquals('red', (new CardGraphic(1, 'diamonds'))->getColor());
        $this->assertEquals('black', (new CardGraphic(1, 'spades'))->getColor());
        $this->assertEquals('black', (new CardGraphic(1, 'clubs'))->getColor());
    }

    public function testJsonSerializeReturnsExpectedStructure(): void
    {
        $card = new CardGraphic(7, 'clubs');
        $json = $card->jsonSerialize();

        $this->assertArrayHasKey('value', $json);
        $this->assertArrayHasKey('suit', $json);
        $this->assertEquals(7, $json['value']);
        $this->assertEquals('clubs', $json['suit']);
    }
}

<?php

namespace App\Card;

class Deck implements \JsonSerializable
{
    private array $cards = [];

    public function __construct(bool $graphic = false, array $preloaded = [])
    {
        if ($preloaded) {
            foreach ($preloaded as $item) {
                $this->cards[] = new CardGraphic($item['value'], $item['suit']);
            }
            return;
        }

        $suits = [
            'spades',
            'hearts',
            'diamonds',
            'clubs'
        ];

        foreach ($suits as $suit) {
            for ($i = 1; $i <= 13; $i++) {
                if ($graphic) {
                    $card = new CardGraphic($i, $suit);
                } else {
                    $card = new Card($i, $suit);
                }

                $this->cards[] = $card;
            }
        }
    }

    public function getCards(): array
    {
        return $this->cards;
    }

    public function getCardsBySuit(string $suit): array
    {
        $matchingCards = [];

        foreach ($this->cards as $card) {
            if ($card->getSuit() === $suit) {
                $matchingCards[] = $card;
            }
        }

        return $matchingCards;
    }

    public function getSortedCardsBySuit(string $suit): array
    {
        $cards = $this->getCardsBySuit($suit);

        $values = [];
        foreach ($cards as $card) {
            $values[] = $card->getValue();
        }

        array_multisort($values, SORT_ASC, $cards);

        return $cards;
    }

    public function shuffle(): void
    {
        shuffle($this->cards);
    }

    public function draw(): ?Card
    {
        return array_shift($this->cards);
    }

    public function jsonSerialize(): array
    {
        return $this->cards;
    }
}

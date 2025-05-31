<?php

namespace App\Card;

class Deck implements \JsonSerializable
{
    /**
     * @var Card[]
     */
    private array $cards = [];

    /**
     * @param bool $graphic Whether or not to use graphic cards.
     * @param array<int, array{value: int, suit: string}> $preloaded Cards to preload into the deck (optional).
     */
    public function __construct(bool $graphic = false, array $preloaded = [])
    {
        if ($preloaded !== []) {
            foreach ($preloaded as $item) {
                $this->cards[] = new CardGraphic($item['value'], $item['suit']);
            }
            return;
        }

        $suits = ['spades', 'hearts', 'diamonds', 'clubs'];

        foreach ($suits as $suit) {
            for ($i = 1; $i <= 13; $i++) {
                $card = $graphic ? new CardGraphic($i, $suit) : new Card($i, $suit);
                $this->cards[] = $card;
            }
        }
    }

    /**
     * Get all cards in the deck.
     *
     * @return Card[]
     */
    public function getCards(): array
    {
        return $this->cards;
    }

    /**
     * Get all cards from the deck matching a specific suit.
     *
     * @param string $suit
     *
     * @return Card[]
     */
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

    /**
     * Get sorted cards by suit in ascending order of value.
     *
     * @param string $suit
     *
     * @return Card[]
     */
    public function getSortedCardsBySuit(string $suit): array
    {
        $cards = $this->getCardsBySuit($suit);

        $values = [];
        foreach ($cards as $card) {
            $values[] = $card->getValue();
        }
        /** @scrutinizer ignore-type */
        array_multisort($values, /** @scrutinizer ignore-type */ SORT_ASC, $cards);

        return $cards;
    }

    /**
     * Shuffle the deck randomly.
     */
    public function shuffle(): void
    {
        shuffle($this->cards);
    }

    /**
     * Draw a card from the deck (removes and returns the top card).
     *
     * @return Card|null
     */
    public function draw(): ?Card
    {
        $card = array_shift($this->cards);
        return $card instanceof Card ? $card : null;
    }

    /**
     * Serialize the deck for JSON output.
     *
     * @return Card[]
     */
    public function jsonSerialize(): array
    {
        return $this->cards;
    }
}

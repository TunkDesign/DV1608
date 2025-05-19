<?php

namespace App\Card;

class CardGraphic extends Card implements \JsonSerializable
{
    /**
     * @var array<string, string[]>
     */
    private array $representations = [
        'spades' => [
            '🂡', '🂢', '🂣', '🂤', '🂥', '🂦', '🂧', '🂨', '🂩', '🂪', '🂫', '🂭', '🂮',
        ],
        'hearts' => [
            '🂱', '🂲', '🂳', '🂴', '🂵', '🂶', '🂷', '🂸', '🂹', '🂺', '🂻', '🂽', '🂾',
        ],
        'diamonds' => [
            '🃁', '🃂', '🃃', '🃄', '🃅', '🃆', '🃇', '🃈', '🃉', '🃊', '🃋', '🃍', '🃎',
        ],
        'clubs' => [
            '🃑', '🃒', '🃓', '🃔', '🃕', '🃖', '🃗', '🃘', '🃙', '🃚', '🃛', '🃝', '🃞',
        ],
    ];

    public function __construct(int $value = 1, string $suit = 'spades')
    {
        parent::__construct($value, $suit);
    }

    /**
     * Get the Unicode string representing the card.
     */
    public function getAsString(): string
    {
        return $this->representations[$this->suit][$this->value - 1] ?? '[Invalid card]';
    }

    /**
     * Get the color (red/black) based on suit.
     */
    public function getColor(): string
    {
        return in_array($this->suit, ['hearts', 'diamonds'], true) ? 'red' : 'black';
    }

    /**
     * Serialize the card to JSON.
     *
     * @return array<string, int|string>
     */
    public function jsonSerialize(): array
    {
        return [
            'value' => $this->value,
            'suit' => $this->suit,
        ];
    }
}

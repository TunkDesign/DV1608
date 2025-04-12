<?php

namespace App\Card;

class CardGraphic extends Card implements \JsonSerializable
{
    private array $representations = [
        'spades' => [
            '🂡', '🂢', '🂣', '🂤', '🂥', '🂦', '🂧', '🂨', '🂩', '🂪', '🂫', '🂭', '🂮'
        ],
        'hearts' => [
            '🂱', '🂲', '🂳', '🂴', '🂵', '🂶', '🂷', '🂸', '🂹', '🂺', '🂻', '🂽', '🂾'
        ],
        'diamonds' => [
            '🃁', '🃂', '🃃', '🃄', '🃅', '🃆', '🃇', '🃈', '🃉', '🃊', '🃋', '🃍', '🃎'
        ],
        'clubs' => [
            '🃑', '🃒', '🃓', '🃔', '🃕', '🃖', '🃗', '🃘', '🃙', '🃚', '🃛', '🃝', '🃞'
        ]
    ];

    public function __construct(int $value = 1, string $suit = 'spades')
    {
        parent::__construct($value, $suit);
    }

    public function getAsString(): string
    {
        return $this->representations[$this->suit][$this->value - 1] ?? '[Invalid card]';
    }


    public function getColor(): string
    {
        return in_array($this->suit, ['hearts', 'diamonds']) ? 'red' : 'black';
    }

    public function jsonSerialize(): array
    {
        return [
            'value' => $this->value,
            'suit' => $this->suit
        ];
    }
}

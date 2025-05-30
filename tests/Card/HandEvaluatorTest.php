<?php

namespace App\Tests\Card;

use App\Card\CardGraphic;
use PHPUnit\Framework\TestCase;
use App\Card\Player;
use App\Card\Poker;
use App\Card\HandEvaluator;
use App\Card\Rule\{
    RoyalFlushRule,
    StraightFlushRule,
    FourOfAKindRule,
    FullHouseRule,
    FlushRule,
    StraightRule,
    ThreeOfAKindRule,
    TwoPairRule,
    OnePairRule,
};

/**
 * Test cases for class HandEvaluator.
 */
class HandEvaluatorTest extends TestCase
{
    public function testEvaluator(): void
    {

        $evaluator = new HandEvaluator([
            new RoyalFlushRule(),
            new StraightFlushRule(),
            new FourOfAKindRule(),
            new FullHouseRule(),
            new FlushRule(),
            new StraightRule(),
            new ThreeOfAKindRule(),
            new TwoPairRule(),
            new OnePairRule()
        ]);

        $game = new Poker();

        $game->addPlayer(new Player('Ewa'));
        $game->addPlayer(new Player('Karl'));

        $playerEwa = $game->getPlayers()[0];
        $playerKarl = $game->getPlayers()[1];
        
        $playerEwa->addCard(new CardGraphic('2'));
        $playerEwa->addCard(new CardGraphic('2'));
        $playerEwa->addCard(new CardGraphic('4'));
        $playerEwa->addCard(new CardGraphic('4'));
        $playerEwa->addCard(new CardGraphic('4'));

        $cardHandEwa = $evaluator->evaluate($playerEwa->getHand());

        $this->assertEquals($cardHandEwa, 'Full House');
        
        $playerKarl->addCard(new CardGraphic('10', 'spades'));
        $playerKarl->addCard(new CardGraphic('8', 'diamonds'));
        $playerKarl->addCard(new CardGraphic('5', 'hearts'));
        $playerKarl->addCard(new CardGraphic('13', 'clubs'));
        $playerKarl->addCard(new CardGraphic('2', 'diamonds'));

        $cardHandKarl = $evaluator->evaluate($playerKarl->getHand());

        $this->assertEquals($cardHandKarl, 'High Card');
    }
}

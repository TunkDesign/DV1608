<?php
namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\Player;
use App\Card\CardGraphic;
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
    HighCardRule
};

/**
 * Test cases for class Rules.
 */
class RulesTest extends TestCase
{
    public function testRoyalFlush(): void
    {
        $evaluator = new HandEvaluator([new RoyalFlushRule()]);
        $player = new Player('Test');

        $player->addCard(new CardGraphic(10, 'spades'));
        $player->addCard(new CardGraphic(11, 'spades'));
        $player->addCard(new CardGraphic(12, 'spades'));
        $player->addCard(new CardGraphic(13, 'spades'));
        $player->addCard(new CardGraphic(1, 'spades'));

        $this->assertEquals('Royal Flush', $evaluator->evaluate($player->getHand()));
    }

    public function testStraightFlush(): void
    {
        $evaluator = new HandEvaluator([new StraightFlushRule()]);
        $player = new Player('Test');

        $player->addCard(new CardGraphic(5, 'hearts'));
        $player->addCard(new CardGraphic(6, 'hearts'));
        $player->addCard(new CardGraphic(7, 'hearts'));
        $player->addCard(new CardGraphic(8, 'hearts'));
        $player->addCard(new CardGraphic(9, 'hearts'));

        $this->assertEquals('Straight Flush', $evaluator->evaluate($player->getHand()));
    }

    public function testFourOfAKind(): void
    {
        $evaluator = new HandEvaluator([new FourOfAKindRule()]);
        $player = new Player('Test');

        $player->addCard(new CardGraphic(8, 'spades'));
        $player->addCard(new CardGraphic(8, 'hearts'));
        $player->addCard(new CardGraphic(8, 'clubs'));
        $player->addCard(new CardGraphic(8, 'diamonds'));
        $player->addCard(new CardGraphic(3, 'spades'));

        $this->assertEquals('Four of a Kind', $evaluator->evaluate($player->getHand()));
    }

    public function testFullHouse(): void
    {
        $evaluator = new HandEvaluator([new FullHouseRule()]);
        $player = new Player('Test');

        $player->addCard(new CardGraphic(4, 'hearts'));
        $player->addCard(new CardGraphic(4, 'clubs'));
        $player->addCard(new CardGraphic(4, 'spades'));
        $player->addCard(new CardGraphic(2, 'diamonds'));
        $player->addCard(new CardGraphic(2, 'hearts'));

        $this->assertEquals('Full House', $evaluator->evaluate($player->getHand()));
    }

    public function testFlush(): void
    {
        $evaluator = new HandEvaluator([new FlushRule()]);
        $player = new Player('Test');

        $player->addCard(new CardGraphic(2, 'diamonds'));
        $player->addCard(new CardGraphic(5, 'diamonds'));
        $player->addCard(new CardGraphic(7, 'diamonds'));
        $player->addCard(new CardGraphic(9, 'diamonds'));
        $player->addCard(new CardGraphic(12, 'diamonds'));

        $this->assertEquals('Flush', $evaluator->evaluate($player->getHand()));
    }

    public function testStraight(): void
    {
        $evaluator = new HandEvaluator([new StraightRule()]);
        $player = new Player('Test');

        $player->addCard(new CardGraphic(3, 'spades'));
        $player->addCard(new CardGraphic(4, 'hearts'));
        $player->addCard(new CardGraphic(5, 'diamonds'));
        $player->addCard(new CardGraphic(6, 'clubs'));
        $player->addCard(new CardGraphic(7, 'hearts'));

        $this->assertEquals('Straight', $evaluator->evaluate($player->getHand()));
    }

    public function testThreeOfAKind(): void
    {
        $evaluator = new HandEvaluator([new ThreeOfAKindRule()]);
        $player = new Player('Test');

        $player->addCard(new CardGraphic(6, 'spades'));
        $player->addCard(new CardGraphic(6, 'hearts'));
        $player->addCard(new CardGraphic(6, 'clubs'));
        $player->addCard(new CardGraphic(9, 'diamonds'));
        $player->addCard(new CardGraphic(12, 'spades'));

        $this->assertEquals('Three of a Kind', $evaluator->evaluate($player->getHand()));
    }

    public function testTwoPair(): void
    {
        $evaluator = new HandEvaluator([new TwoPairRule()]);
        $player = new Player('Test');

        $player->addCard(new CardGraphic(3, 'hearts'));
        $player->addCard(new CardGraphic(3, 'clubs'));
        $player->addCard(new CardGraphic(5, 'spades'));
        $player->addCard(new CardGraphic(5, 'hearts'));
        $player->addCard(new CardGraphic(9, 'diamonds'));

        $this->assertEquals('Two Pair', $evaluator->evaluate($player->getHand()));
    }

    public function testOnePair(): void
    {
        $evaluator = new HandEvaluator([new OnePairRule()]);
        $player = new Player('Test');

        $player->addCard(new CardGraphic(10, 'spades'));
        $player->addCard(new CardGraphic(10, 'clubs'));
        $player->addCard(new CardGraphic(2, 'hearts'));
        $player->addCard(new CardGraphic(4, 'diamonds'));
        $player->addCard(new CardGraphic(6, 'clubs'));

        $this->assertEquals('One Pair', $evaluator->evaluate($player->getHand()));
    }

    public function testHighCard(): void
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
            new OnePairRule(),
            new HighCardRule()
        ]);
        $player = new Player('Test');

        $player->addCard(new CardGraphic(2, 'diamonds'));
        $player->addCard(new CardGraphic(5, 'spades'));
        $player->addCard(new CardGraphic(7, 'hearts'));
        $player->addCard(new CardGraphic(9, 'clubs'));
        $player->addCard(new CardGraphic(13, 'spades'));

        $this->assertEquals('High Card', $evaluator->evaluate($player->getHand()));
    }
}

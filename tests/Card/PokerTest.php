<?php

use App\Card\CardGraphic;
use App\Card\ComputerPlayer;
use App\Card\MonkeyPlayer;
use App\Card\Player;
use App\Card\Poker;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Poker.
 */
class PokerTest extends TestCase
{
    public function testAddAndGetPlayers(): void
    {
        $game = new Poker();

        $game->addPlayer(new Player('Ewa'));
        $game->addPlayer(new Player('Karl'));

        $players = $game->getPlayers();

        $this->assertCount(2, $players);
    }

    public function testGetCurrentPlayer(): void
    {
        $game = new Poker();

        $game->addPlayer(new Player('Ewa'));
        $game->addPlayer(new Player('Karl'));

        $oldPlayer = $game->getPlayer();
        $this->assertSame('Ewa', $oldPlayer->getName());

        $game->nextPlayer();

        $newPlayer = $game->getPlayer();
        $this->assertSame('Karl', $newPlayer->getName());
        $this->assertNotSame($oldPlayer, $newPlayer);
    }

    public function testNextPlayer(): void
    {
        $game = new Poker();

        $game->addPlayer(new Player('Ewa'));
        $game->addPlayer(new Player('Karl'));

        $this->assertSame(0, $game->getPlayerIndex());

        $game->nextPlayer();
        $this->assertSame(1, $game->getPlayerIndex());
    }

    public function testPlayerDrawNewCard(): void
    {
        $game = new Poker();

        $game->addPlayer(new Player('Ewa'));

        $player = $game->getPlayer();
        $drawnCard = $game->draw($player);
        $this->assertNotNull($drawnCard);
        $this->assertCount(1, $player->getHand()->getCards());
    }

    public function testDrawNewCardIfPlayerHasFolded(): void
    {
        $game = new Poker();

        $game->addPlayer(new Player('Ewa'));

        $player = $game->getPlayer();
        $player->fold();
        $this->assertFalse($game->draw($player));
    }

    public function testHasEnded(): void
    {
        $game = new Poker();

        $gameStatus = $game->hasEnded();
        $this->assertFalse($gameStatus);

        $game->endGame();
        $gameStatus = $game->hasEnded();
        $this->assertTrue($gameStatus);
    }

    public function testPlayerRedrawCards(): void
    {
        $game = new Poker();

        $game->addPlayer(new Player('Ewa'));

        $player = $game->getPlayer();
        $game->draw($player);
        $game->draw($player);
        $game->draw($player);
        $game->draw($player);
        $game->draw($player);

        $playerCards = $player->getHand()->getCards();
        $this->assertCount(5, $playerCards);

        $cardIndexesToRedraw = [0, 2, 4];

        $game->redraw($player, $cardIndexesToRedraw);

        $newPlayerCards = $player->getHand()->getCards();

        $this->assertCount(5, $newPlayerCards);
        $this->assertNotSame($playerCards, $newPlayerCards);
    }

    public function testEvaluateHand(): void
    {
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

        $cardHandEwa = $game->evaluateHand($playerEwa);

        $this->assertEquals($cardHandEwa, 'Full House');

        $playerKarl->addCard(new CardGraphic('10', 'spades'));
        $playerKarl->addCard(new CardGraphic('8', 'diamonds'));
        $playerKarl->addCard(new CardGraphic('5', 'hearts'));
        $playerKarl->addCard(new CardGraphic('13', 'clubs'));
        $playerKarl->addCard(new CardGraphic('2', 'diamonds'));

        $cardHandKarl = $game->evaluateHand($playerKarl);

        $this->assertEquals($cardHandKarl, 'High Card');
    }

    public function testGetWinner(): void
    {
        $game = new Poker();

        $player1 = new Player('Ewa');
        $player2 = new Player('Karl');

        $game->addPlayer($player1);
        $game->addPlayer($player2);

        $player1->addCard(new CardGraphic('2'));
        $player1->addCard(new CardGraphic('2'));
        $player1->addCard(new CardGraphic('4'));
        $player1->addCard(new CardGraphic('4'));
        $player1->addCard(new CardGraphic('4'));

        $player2->addCard(new CardGraphic('10'));
        $player2->addCard(new CardGraphic('10'));
        $player2->addCard(new CardGraphic('5'));
        $player2->addCard(new CardGraphic('5'));
        $player2->addCard(new CardGraphic('8'));

        $winner = $game->getWinner();
        $this->assertInstanceOf(Player::class, $winner);
        $this->assertSame('Ewa', $winner->getName());
    }

    public function testGetWinnerWithDifferentValues(): void
    {
        $game = new Poker();

        $player1 = new Player('Ewa');
        $player2 = new Player('Karl');

        $game->addPlayer($player1);
        $game->addPlayer($player2);

        $player1->addCard(new CardGraphic('10'));
        $player1->addCard(new CardGraphic('10'));
        $player1->addCard(new CardGraphic('9'));
        $player1->addCard(new CardGraphic('5'));
        $player1->addCard(new CardGraphic('2'));

        $player2->addCard(new CardGraphic('10'));
        $player2->addCard(new CardGraphic('10'));
        $player2->addCard(new CardGraphic('13'));
        $player2->addCard(new CardGraphic('7'));
        $player2->addCard(new CardGraphic('3'));

        $winner = $game->getWinner();

        $this->assertInstanceOf(Player::class, $winner);
        $this->assertSame('Karl', $winner->getName());
    }

    public function testAddToPot(): void
    {
        $game = new Poker();

        $this->assertEquals(0, $game->getPot());

        $game->addToPot(100);

        $this->assertEquals(100, $game->getPot());
    }

    public function testRunComputerBets(): void
    {
        $game = new Poker();

        $monkey = new MonkeyPlayer('Chimp');
        $game->addPlayer($monkey);

        $cpuPair = new ComputerPlayer('PairBot');
        $cpuPair->addCard(new CardGraphic('4'));
        $cpuPair->addCard(new CardGraphic('4'));
        $cpuPair->addCard(new CardGraphic('7'));
        $cpuPair->addCard(new CardGraphic('9'));
        $cpuPair->addCard(new CardGraphic('12'));
        $game->addPlayer($cpuPair);

        $cpuThree = new ComputerPlayer('ThreeBot');
        $cpuThree->addCard(new CardGraphic('5'));
        $cpuThree->addCard(new CardGraphic('5'));
        $cpuThree->addCard(new CardGraphic('5'));
        $cpuThree->addCard(new CardGraphic('8'));
        $cpuThree->addCard(new CardGraphic('11'));
        $game->addPlayer($cpuThree);

        $cpuFour = new ComputerPlayer('FourBot');
        $cpuFour->addCard(new CardGraphic('9'));
        $cpuFour->addCard(new CardGraphic('9'));
        $cpuFour->addCard(new CardGraphic('9'));
        $cpuFour->addCard(new CardGraphic('9'));
        $cpuFour->addCard(new CardGraphic('2'));
        $game->addPlayer($cpuFour);

        $bets = $game->runComputerBets();

        $this->assertCount(4, $bets);
        $this->assertEquals(['name' => 'Chimp', 'amount' => 10], $bets[0]);
        $this->assertEquals(['name' => 'PairBot', 'amount' => 30], $bets[1]);
        $this->assertEquals(['name' => 'ThreeBot', 'amount' => 60], $bets[2]);
        $this->assertEquals(['name' => 'FourBot', 'amount' => 100], $bets[3]);

        $this->assertEquals(200, $game->getPot());
    }


    public function testPlaceBet(): void
    {
        $game = new Poker();

        $game->addPlayer(new Player('Ewa'));

        $this->assertEquals(0, $game->getPot());

        $game->placeBet($game->getPlayer(), 100);

        $this->assertEquals(100, $game->getPot());
    }

    public function testFullHands(): void
    {
        $game = new Poker();

        $game->addPlayer(new Player('Ewa'));

        $this->assertFalse($game->fullHands());

        $game->getPlayer()->addCard(new CardGraphic('10'));
        $game->getPlayer()->addCard(new CardGraphic('10'));
        $game->getPlayer()->addCard(new CardGraphic('9'));
        $game->getPlayer()->addCard(new CardGraphic('5'));
        $game->getPlayer()->addCard(new CardGraphic('2'));

        $this->assertTrue($game->fullHands());
    }

    public function testStaging(): void
    {
        $game = new Poker();

        $this->assertEquals('deal', $game->getStage());

        $game->setStage('test');

        $this->assertEquals('test', $game->getStage());
    }
}

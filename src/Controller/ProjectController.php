<?php

namespace App\Controller;

use App\Card\Player;
use App\Card\Poker;
use App\Card\ComputerPlayer;
use App\Card\MonkeyPlayer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class ProjectController extends AbstractController
{
    #[Route('/proj', name: 'poker_start')]
    public function index(): Response
    {
        return $this->render('project/start.html.twig');
    }
    
    #[Route('/proj/api', name: 'poker_api')]
    public function api(): Response
    {
        return $this->render('project/api.html.twig');
    }
    
    #[Route('/proj/about', name: 'poker_about')]
    public function about(): Response
    {
        return $this->render('project/about.html.twig');
    }

    #[Route('/proj/board', name: 'poker_board')]
    public function board(SessionInterface $session): Response
    {
        if ($session->get('poker')) {
            $game = $session->get('poker');

            return $this->render('project/board.html.twig', [
                'poker' => $game,
            ]);
        }

        return $this->redirectToRoute('poker_start');
    }

    #[Route('/proj/init', name: 'poker_init')]
    public function init(
        SessionInterface $session
    ): Response {
        if ($session->get('poker')) {
            return $this->redirectToRoute('poker_board');
        }

        return $this->render('project/init.html.twig');
    }

    #[Route('/proj/reset', name: 'poker_reset')]
    public function resetBoard(
        SessionInterface $session
    ): Response {
        $session->remove('poker');

        return $this->redirectToRoute('poker_start');
    }


    #[Route('/api/poker/reset', name: 'api_poker_reset')]
    public function reset(
        SessionInterface $session
    ): JsonResponse {
        $session->remove('poker');

        return $this->json([
            'success' => 'Game reset!'
        ]);
    }

    #[Route('/api/poker/start', name: 'api_poker_start', methods: ['POST'])]
    public function startGame(
        Request $request,
        SessionInterface $session
    ): JsonResponse {
        $game = new Poker();
        $session->set('poker', $game);

        $game->addPlayer(new MonkeyPlayer('Monkey'));
        $game->addPlayer(new ComputerPlayer('Computer'));
        $game->addPlayer(new Player($request->request->get('name')));


        return $this->json([
            'success' => 'Game started!'
        ]);
    }

    #[Route('/api/poker', name: 'api_poker')]
    public function info(SessionInterface $session): JsonResponse
    {
        if ($session->get('poker')) {
            $game = $session->get('poker');

            return $this->json([
                'players' => $game->getPlayers(),
                'ended' => $game->hasEnded()
            ]);
        }

        return $this->json([
            'error' => 'No game in session.'
        ], 400);
    }

    #[Route('/api/poker/draw', name: 'api_poker_draw')]
    public function draw(SessionInterface $session): JsonResponse
    {
        if ($session->get('poker')) {
            $game = $session->get('poker');

            $playerIndex = $game->getPlayerIndex();
            $player = $game->getPlayer();
            $drawn = $game->draw($player);

            $response = [
                'card' => false,
                'index' => $playerIndex
            ];

            if (!$player instanceof ComputerPlayer && !$player instanceof MonkeyPlayer) {
                $response['card'] = [
                    'value' => $drawn->getValue(),
                    'suit' => $drawn->getSuit(),
                    'string' => $drawn->getAsString(),
                    'color' => $drawn->getColor()
                ];
            }

            $game->nextPlayer();

            if ($game->fullHands()) {
                $game->setStage('bet1');
            }

            return $this->json($response);
        }

        return $this->json([
            'error' => 'No game in session.'
        ], 400);
    }


    #[Route('/api/poker/exchange', name: 'api_poker_exchange', methods: ['POST'])]
    public function exchange(Request $request, SessionInterface $session): JsonResponse
    {
        if ($session->get('poker')) {
            $cardIndex = json_decode($request->getContent(), true);

            $game = $session->get('poker');
            $players = $game->getPlayers();

            $player = $players[2];
            $game->redraw($player, $cardIndex['cards']);

            $players[0]->makeMove($game, $players[0]);

            $players[1]->makeMove($game, $players[1]);

            $redrawnCards = [];
            foreach ($player->getHand()->getCards() as $card) {
                $redrawnCards[] = [
                    'value' => $card->getValue(),
                    'suit' => $card->getSuit(),
                    'string' => $card->getAsString(),
                    'color' => $card->getColor()
                ];
            }

            $game->setStage('bet2');

            return $this->json([
                'cards' => $redrawnCards
            ]);
        }

        return $this->json([
            'error' => 'No game in session.'
        ], 400);
    }

    #[Route('/api/poker/showdown', name: 'api_poker_showdown')]
    public function showdown(SessionInterface $session): JsonResponse
    {

        if ($session->get('poker')) {

            $game = $session->get('poker');
            $players = $game->getPlayers();

            $playerHands = [];

            foreach ($players as $i => $player) {


                $redrawnCards = [];
                foreach ($player->getHand()->getCards() as $card) {
                    $redrawnCards[] = [
                        'value' => $card->getValue(),
                        'suit' => $card->getSuit(),
                        'string' => $card->getAsString(),
                        'color' => $card->getColor()
                    ];
                }

                $playerHands[] = [
                    'index' => $i,
                    'name' => $player->getName(),
                    'cards' => $redrawnCards,
                    'value' => $player->getHand()->getValue(),
                    'hand' => $player->getPlayHand(),
                    'balance' => $player->getBalance()
                ];
            }

            return $this->json([
                'players' => $playerHands
            ]);
        }

        return $this->json([
            'error' => 'No game in session.'
        ], 400);
    }

    #[Route('/api/poker/winner', name: 'api_poker_winner')]
    public function winner(SessionInterface $session): JsonResponse
    {

        if ($session->get('poker')) {

            $game = $session->get('poker');

            return $this->json([
                'winner' => $game->getWinner()
            ]);
        }

        return $this->json([
            'error' => 'No game in session.'
        ], 400);
    }

    #[Route('/api/poker/bet/computer', name: 'api_poker_bet_computer')]
    public function startBets(SessionInterface $session): JsonResponse
    {
        $game = $session->get('poker');
        $bets = $game->runComputerBets();

        $game->setStage('exchange');

        return $this->json([
            'bets' => $bets,
            'pot' => $game->getPot()
        ]);
    }


    #[Route('/api/poker/bet', name: 'api_poker_bet', methods: ['POST'])]
    public function playerBet(Request $request, SessionInterface $session): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $amount = (int) $data['amount'];

        $game = $session->get('poker');
        $player = $game->getPlayers()[2];

        $game->placeBet($player, $amount);

        $game->setStage('showdown');

        return $this->json([
            'status' => 'ok',
            'pot' => $game->getPot(),
            'balance' => $player->getBalance()
        ]);
    }

    #[Route('/api/poker/state', name: 'api_poker_state')]
    public function state(SessionInterface $session): JsonResponse
    {
        $game = $session->get('poker');
        if (!$game) {
            return $this->json([
                'error' => 'No game in session'
            ], 400);
        }

        $players = $game->getPlayers();
        $playerData = [];

        foreach ($players as $index => $player) {
            $cards = [];

            if (!$player instanceof ComputerPlayer && !$player instanceof MonkeyPlayer) {
                foreach ($player->getHand()->getCards() as $card) {
                    $cards[] = [
                        'string' => $card->getAsString(),
                        'color' => $card->getColor()
                    ];
                }
            } else {
                foreach ($player->getHand()->getCards() as $_) {
                    $cards[] = [
                        'string' => '🂠',
                        'color' => ''
                    ];
                }
            }

            $playerData[] = [
                'index' => $index,
                'name' => $player->getName(),
                'balance' => $player->getBalance(),
                'cards' => $cards
            ];
        }

        return $this->json([
            'players' => $playerData,
            'pot' => $game->getPot(),
            'currentPlayerIndex' => $game->getPlayerIndex(),
            'stage' => $game->getStage()
        ]);
    }
}

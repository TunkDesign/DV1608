<?php

namespace App\Controller;

use App\Card\Player;
use App\Card\TwentyOne;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class TwentyOneController extends AbstractController
{
    #[Route('/game', name: 'game_start')]
    public function start(): Response
    {
        return $this->render('game/start.html.twig');
    }

    #[Route('/game/play', name: 'game_play')]
    public function play(
        SessionInterface $session
    ): Response {

        if ($session->get('game')) {
            $twentyOne = $session->get('game');
        } else {
            $twentyOne = new TwentyOne();
            $session->set('game', $twentyOne);

            $twentyOne->addPlayer(new Player('Spelare'));
            $twentyOne->addPlayer(new Player('Bank'));
        }

        return $this->render('game/play.html.twig', [
            'ended' => $twentyOne->hasEnded(),
            'players' => $twentyOne->getPlayers(),
            'player' => $twentyOne->getPlayer(),
            'winner' => $twentyOne->getWinner()
        ]);
    }

    #[Route('/game/draw', name: 'game_draw')]
    public function draw(
        SessionInterface $session
    ): Response {

        if ($session->get('game')) {
            $twentyOne = $session->get('game');
        } else {
            return $this->redirectToRoute('game_play');
        }

        if (!$twentyOne->hasEnded()) {
            $player = $twentyOne->getPlayer();

            $twentyOne->draw($player);
        }

        return $this->redirectToRoute('game_play');
    }

    #[Route('/game/hold', name: 'game_hold')]
    public function hold(
        SessionInterface $session
    ): Response {

        if ($session->get('game')) {
            $twentyOne = $session->get('game');
        } else {
            return $this->redirectToRoute('game_play');
        }

        if ($twentyOne->getPlayer() === $twentyOne->getLastPlayer()) {
            $twentyOne->endGame();
        } else {
            $player = $twentyOne->getPlayer();

            $player->hold();

            $twentyOne->nextPlayer();
        }

        return $this->redirectToRoute('game_play');
    }


    #[Route('/game/reset', name: 'game_reset')]
    public function reset(
        SessionInterface $session
    ): Response {
        $session->remove('game');

        $this->addFlash(
            'notice',
            'Spelet återställdes!'
        );

        return $this->redirectToRoute('game_play');
    }

    #[Route('/game/doc', name: 'game_doc')]
    public function doc(): Response
    {
        return $this->render('game/doc.html.twig');
    }
}

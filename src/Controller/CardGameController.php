<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\Deck;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CardGameController extends AbstractController
{

    #[Route('/card', name: 'card_start')]
    public function home(): Response
    {
        return $this->render('card/home.html.twig');
    }

    #[Route('/card/deck', name: 'card_deck')]
    public function deck(): Response
    {

        // Init a new Deck
        $deck = new Deck(true);

        // Save each suit in their own variables.
        $spades = $deck->getCardsBySuit('spades');
        $hearts = $deck->getCardsBySuit('hearts');
        $diamonds = $deck->getCardsBySuit('diamonds');
        $clubs = $deck->getCardsBySuit('clubs');

        return $this->render('card/deck.html.twig', [
            'spades' => $spades,
            'hearts' => $hearts,
            'diamonds' => $diamonds,
            'clubs' => $clubs
        ]);
    }
}
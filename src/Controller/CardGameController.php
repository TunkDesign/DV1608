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

    #[Route('/card/deck/shuffle', name: 'card_shuffle')]
    public function shuffle(
        SessionInterface $session
    ): Response
    {
        // Init a new Deck
        $deck = new Deck(true);

        // Shuffle the deck.
        $deck->shuffle();

        // Save deck in session
        $session->set('deck', json_encode($deck));

        $cards = [];
        // Loop through the deck and assign the correct color.
        foreach ($deck->getCards() as $card) {
            $cards[] = [
                'name' => $card->getAsString(),
                'color' => $card->getColor()
            ];
        }

        return $this->render('card/shuffle.html.twig', [
            'deck' => $cards
        ]);
    }

    #[Route('/card/deck/draw', name: 'card_draw')]
    public function draw(
        SessionInterface $session
    ): Response
    {
        // Get Deck json data from session.
        $jsonDeck = $session->get('deck');

        // Init a new Deck based on session.
        $rawData = json_decode($jsonDeck, true);
        $deck = new Deck(true, $rawData);

        // Draw a card.
        $drawn = $deck->draw();

        // Save modified deck to session.
        $session->set('deck', json_encode($deck));

        /** @var CardGraphic $drawn */
        // Add the correct name and color.
        $drawnCards[] = [
                'name' => $drawn->getAsString(),
                'color' => $drawn->getColor()
        ];

        return $this->render('card/draw.html.twig', [
            'drawn' => $drawnCards,
            'cards_left' => count($deck->getCards())
        ]);
    }

    #[Route('/card/deck/draw/{num<\d+>}', name: 'card_draw_number')]
    public function drawNumber(int $num,
        SessionInterface $session
    ): Response
    {
        if ($num > 52) {
            throw new \Exception('Can not draw more cards than the deck contains!');
        }

        // Get deck json data from session.
        $jsonDeck = $session->get('deck');

        // Init a new deck based on session.
        $rawData = json_decode($jsonDeck, true);
        $deck = new Deck(true, $rawData);

        // Loop through and draw a card for each specified number.
        $drawnCards = [];
        for ($i = 0; $i < $num; $i++) {
            $card = $deck->draw();

            /** @var CardGraphic $card */
            if ($card) {
                $drawnCards[] = [
                    'name' => $card->getAsString(),
                    'color' => $card->getColor()
                ];
            } else {
                break; 
            }
        }

        // Save the modified deck to session.
        $session->set('deck', json_encode($deck));

        return $this->render('card/draw.html.twig', [
            'drawn' => $drawnCards,
            'cards_left' => count($deck->getCards())
        ]);
    }
}
}
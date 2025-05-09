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
    public function deck(
        SessionInterface $session
    ): Response {

        // Check if a deck exists in session.
        if ($session->get('deck')) {
            // Get Deck json data from session.
            $jsonDeck = $session->get('deck');

            // Init a new Deck based on session.
            $rawData = json_decode($jsonDeck, true);
            $deck = new Deck(true, $rawData);
        } else {
            // Init a new Deck
            $deck = new Deck(true);

            // Save deck in session
            $session->set('deck', json_encode($deck));
        }

        // Save each suit in their own variables.
        $spades = $deck->getSortedCardsBySuit('spades');
        $hearts = $deck->getSortedCardsBySuit('hearts');
        $diamonds = $deck->getSortedCardsBySuit('diamonds');
        $clubs = $deck->getSortedCardsBySuit('clubs');

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
    ): Response {

        // Check if a deck exists in session.
        if ($session->get('deck')) {
            // Get Deck json data from session.
            $jsonDeck = $session->get('deck');

            // Init a new Deck based on session.
            $rawData = json_decode($jsonDeck, true);
            $deck = new Deck(true, $rawData);
        } else {
            // Init a new Deck
            $deck = new Deck(true);

            // Save deck in session
            $session->set('deck', json_encode($deck));
        }

        // Shuffle the deck.
        $deck->shuffle();

        // Save deck in session
        $session->set('deck', json_encode($deck));

        $cards = [];
        // Loop through the deck and assign the correct color.
        foreach ($deck->getCards() as $card) {
            if ($card instanceof CardGraphic) {
                $cards[] = [
                    'name' => $card->getAsString(),
                    'color' => $card->getColor()
                ];
            }
        }

        return $this->render('card/shuffle.html.twig', [
            'deck' => $cards
        ]);
    }

    #[Route('/card/deck/draw', name: 'card_draw')]
    public function draw(
        SessionInterface $session
    ): Response {

        // Check if a deck exists in session.
        if ($session->get('deck')) {
            // Get Deck json data from session.
            $jsonDeck = $session->get('deck');

            // Init a new Deck based on session.
            $rawData = json_decode($jsonDeck, true);
            $deck = new Deck(true, $rawData);
        } else {
            // Init a new Deck
            $deck = new Deck(true);

            // Save deck in session
            $session->set('deck', json_encode($deck));
        }

        $drawnCards = [];

        if (count($deck->getCards())) {
            // Draw a card.
            $drawn = $deck->draw();

            // Add the correct name and color.
            if ($drawn instanceof CardGraphic) {
                $drawnCards[] = [
                    'name' => $drawn->getAsString(),
                    'color' => $drawn->getColor()
                ];
            }

            // Save modified deck to session.
            $session->set('deck', json_encode($deck));
        }

        return $this->render('card/draw.html.twig', [
            'drawn' => $drawnCards,
            'cards_left' => count($deck->getCards())
        ]);
    }

    #[Route('/card/deck/draw/{num<\d+>}', name: 'card_draw_number')]
    public function drawNumber(
        int $num,
        SessionInterface $session
    ): Response {
        if ($num > 52) {
            throw new \Exception('Can not draw more cards than the deck contains!');
        }

        // Check if a deck exists in session.
        if ($session->get('deck')) {
            // Get Deck json data from session.
            $jsonDeck = $session->get('deck');

            // Init a new Deck based on session.
            $rawData = json_decode($jsonDeck, true);
            $deck = new Deck(true, $rawData);
        } else {
            // Init a new Deck
            $deck = new Deck(true);

            // Save deck in session
            $session->set('deck', json_encode($deck));
        }

        // Loop through and draw a card for each specified number.
        $drawnCards = [];
        for ($i = 0; $i < $num; $i++) {
            $card = $deck->draw();

            if ($card instanceof CardGraphic) {
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

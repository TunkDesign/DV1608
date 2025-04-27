<?php

namespace App\Controller\Traits;

use App\Card\Deck;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

trait DeckSessionTrait
{
    private function getDeck(SessionInterface $session): Deck
    {
        if ($session->get('deck')) {
            $jsonDeck = $session->get('deck');
            $rawData = json_decode($jsonDeck, true);
            return new Deck(true, $rawData);
        }

        $deck = new Deck(true);
        $session->set('deck', json_encode($deck));
        return $deck;
    }

    private function saveDeck(SessionInterface $session, Deck $deck): void
    {
        $session->set('deck', json_encode($deck));
    }
}

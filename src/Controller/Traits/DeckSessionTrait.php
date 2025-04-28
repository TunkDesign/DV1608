<?php

namespace App\Controller\Traits;

use App\Card\Deck;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Provides helper methods to manage a Deck stored in session.
 */
trait DeckSessionTrait
{
    /**
     * Retrieves the current deck from the session, or creates a new one if none exists.
     *
     * @param SessionInterface $session The session instance.
     * @return Deck The deck instance.
     */
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

    /**
     * Saves the given deck into the session.
     *
     * @param SessionInterface $session The session instance.
     * @param Deck $deck The deck to save.
     */
    private function saveDeck(SessionInterface $session, Deck $deck): void
    {
        $session->set('deck', json_encode($deck));
    }
}

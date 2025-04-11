<?php

namespace App\Controller;

use App\Card\Deck;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route('/api/', name: 'api')]
    public function api(): Response
    {
        $routes = [
            [
                'title' => 'Dagens The Office Citat',
                'description' => 'Returnerar slumpmässiga citat från den populära humorserien The Office.',
                'method' => 'GET',
                'path' => 'quote',
                'example' => 'quote',
                'response' => json_encode([
                    'quote' => "I am not superstitious, but I am a little stitious.",
                    'author' => "Michael Scott",
                    'date' => date('Y-m-d'),
                    'timestamp' => date(DATE_ATOM),
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            ],
            [
                'title' => 'Visa kortlek',
                'description' => 'Returnerar en kortlek sorterad på färg och värde.',
                'method' => 'GET',
                'path' => 'deck',
                'example' => 'deck',
                'response' => json_encode([
                    'spades' => [
                        [
                            'values' => 1,
                            'suit' => 'spades',
                        ],
                        [
                            'values' => 2,
                            'suit' => 'spades',
                        ],
                        '..'
                    ],
                    'hearts' => [
                        [
                            'values' => 1,
                            'suit' => 'hearts',
                        ],
                        [
                            'values' => 2,
                            'suit' => 'hearts',
                        ],
                        '..'
                    ],
                    'diamonds' => [
                        [
                            'values' => 1,
                            'suit' => 'diamonds',
                        ],
                        [
                            'values' => 2,
                            'suit' => 'diamonds',
                        ],
                        '..'
                    ],
                    'clubs' => [
                        [
                            'values' => 1,
                            'suit' => 'clubs',
                        ],
                        [
                            'values' => 2,
                            'suit' => 'clubs',
                        ],
                        '..'
                    ]
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            ],
            [
                'title' => 'Blanda kortlek',
                'description' => 'Returnerar slumpmässigt blandad kortlek och sparar i session.',
                'method' => 'GET',
                'path' => 'deck/shuffle',
                'example' => 'deck/shuffle',
                'response' => json_encode([
                    [
                        'name' => "\ud83c\udcc1",
                        'color' => 'red'
                    ],
                    [
                        "name" => "\ud83c\udca6",
                        "color" => "black"
                    ]
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            ],
            [
                'title' => 'Dra ett kort',
                'description' => 'Returnerar ett kort från kortleken och antal kort kvar. Om man inte blandar kortleken när kortleken är tom, så drar man ett nytt kort från en sorterad kortlek.',
                'method' => 'GET',
                'path' => 'deck/draw',
                'example' => 'deck/draw',
                'response' => json_encode([
                    'drawn' => [[
                        'name' => "\ud83c\udcaa",
                        'color' => 'black'
                    ]],
                    'cards_left' => 30
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            ],
            [
                'title' => 'Dra flera kort',
                'description' => 'Returnerar x kort från kortleken och antal kort kvar. Om man inte blandar kortleken när kortleken är tom, så drar man ett nytt kort från en sorterad kortlek.',
                'method' => 'GET',
                'path' => 'deck/draw/:number:',
                'example' => 'deck/draw/3',
                'response' => json_encode([
                    'drawn' => [
                        [
                            'name' => "\ud83c\udcd7",
                            'color' => 'black'
                        ],
                        [
                            'name' => "\ud83c\udcc8",
                            'color' => 'red'
                        ],
                        [
                            'name' => "\ud83c\udcc9",
                            'color' => 'red'
                        ]
                    ],
                    'cards_left' => 30
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            ]
        ];

        return $this->render('api.html.twig', [
            'routes' => $routes,
        ]);
    }

    #[Route('/api/quote', name: 'api_quote')]
    public function quote(): JsonResponse
    {
        $quotes = [
            [
                'quote' => "I am Beyonce, always.",
                'author' => "Michael Scott"
            ],
            [
                'quote' => "I talk a lot, so I have learned to tune myself out.",
                'author' => "Kelly Kapoor"
            ],
            [
                'quote' => "I am running away from my responsibilities. And it feels good.",
                'author' => "Michael Scott"
            ],
            [
                'quote' => "Sometimes I will start a sentence and I do not even know where it is going. I just hope I find it along the way.",
                'author' => "Michael Scott"
            ],
            [
                'quote' => "Bears. Beets. Battlestar Galactica.",
                'author' => "Jim Halpert"
            ],
            [
                'quote' => "I am fast. To give you a reference point, I am somewhere between a snake and a mongoose… and a panther.",
                'author' => "Dwight Schrute"
            ],
            [
                'quote' => "I am not superstitious, but I am a little stitious.",
                'author' => "Michael Scott"
            ],
            [
                'quote' => "Identity theft is not a joke, Jim! Millions of families suffer every year!",
                'author' => "Dwight Schrute"
            ],
            [
                'quote' => "Sometimes the clothes at Gap Kids are just too flashy, so I am forced to go to the American Girl store and order clothes for large dolls.",
                'author' => "Angela Martin"
            ]
        ];

        $randomQuote = $quotes[array_rand($quotes)];

        $now = new \DateTimeImmutable();

        $response = new JsonResponse([
            'quote' => $randomQuote['quote'],
            'author' => $randomQuote['author'],
            'date' => $now->format('Y-m-d'),
            'timestamp' => $now->format(\DateTime::ATOM), // ISO 8601
        ]);

        $response->setEncodingOptions($response->getEncodingOptions() | JSON_PRETTY_PRINT);

        return $response;
    }

    #[Route('/api/deck', name: 'api_deck')]
    public function deck(
        SessionInterface $session
    ): JsonResponse {

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

        $response = new JsonResponse([
            'spades' => $spades,
            'hearts' => $hearts,
            'diamonds' => $diamonds,
            'clubs' => $clubs
        ]);

        $response->setEncodingOptions($response->getEncodingOptions() | JSON_PRETTY_PRINT);

        return $response;
    }

    #[Route('/api/deck/shuffle', name: 'api_deck_suffle')]
    public function deck_shuffle(
        SessionInterface $session
    ): JsonResponse {

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
            $cards[] = [
                'name' => $card->getAsString(),
                'color' => $card->getColor()
            ];
        }

        $response = new JsonResponse($cards);

        $response->setEncodingOptions($response->getEncodingOptions() | JSON_PRETTY_PRINT);

        return $response;
    }

    #[Route('/api/deck/draw', name: 'api_deck_draw')]
    public function deck_draw(
        SessionInterface $session
    ): JsonResponse {

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

            // Save modified deck to session.
            $session->set('deck', json_encode($deck));

            /** @var CardGraphic $drawn */
            // Add the correct name and color.
            $drawnCards[] = [
                'name' => $drawn->getAsString(),
                'color' => $drawn->getColor()
            ];
        }

        $response = new JsonResponse([
            'drawn' => $drawnCards,
            'cards_left' => count($deck->getCards())
        ]);

        $response->setEncodingOptions($response->getEncodingOptions() | JSON_PRETTY_PRINT);

        return $response;
    }

    #[Route('/api/deck/draw/{num<\d+>}', name: 'api_deck_draw_number')]
    public function deck_draw_number(
        int $num,
        SessionInterface $session
    ): JsonResponse {

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

        // Save modified deck to session.
        $session->set('deck', json_encode($deck));

        $response = new JsonResponse([
            'drawn' => $drawnCards,
            'cards_left' => count($deck->getCards())
        ]);

        $response->setEncodingOptions($response->getEncodingOptions() | JSON_PRETTY_PRINT);

        return $response;
    }
}

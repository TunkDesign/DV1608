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
                'description' => 'Returnerar ett kort från kortleken och antal kort kvar.',
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
        ];

        return $this->render('api.html.twig', [
            'routes' => $routes,
        ]);
    }

    #[Route('/api/quote', name: 'api_quote', methods: ['GET'])]
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

    #[Route('/api/deck', name: 'api_deck', methods: ['GET'])]
    public function deck(): JsonResponse
    {

        // Init a new Deck
        $deck = new Deck(true);

        // Save each suit in their own variables.
        $spades = $deck->getCardsBySuit('spades');
        $hearts = $deck->getCardsBySuit('hearts');
        $diamonds = $deck->getCardsBySuit('diamonds');
        $clubs = $deck->getCardsBySuit('clubs');

        $response = new JsonResponse([
            'spades' => $spades,
            'hearts' => $hearts,
            'diamonds' => $diamonds,
            'clubs' => $clubs
        ]);

        $response->setEncodingOptions($response->getEncodingOptions() | JSON_PRETTY_PRINT);

        return $response;
    }

    #[Route('/api/deck/shuffle', name: 'api_deck_suffle', methods: ['GET'])]
    public function deck_shuffle(
        SessionInterface $session
    ): JsonResponse
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

        $response = new JsonResponse($cards);

        $response->setEncodingOptions($response->getEncodingOptions() | JSON_PRETTY_PRINT);

        return $response;
    }

    #[Route('/api/deck/draw', name: 'api_deck_draw', methods: ['GET'])]
    public function deck_draw(
        SessionInterface $session
    ): JsonResponse
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

        $response = new JsonResponse([
            'drawn' => $drawnCards,
            'cards_left' => count($deck->getCards())
        ]);

        $response->setEncodingOptions($response->getEncodingOptions() | JSON_PRETTY_PRINT);

        return $response;
    }

}

<?php

namespace App\Controller;

use App\Card;
use App\Card\CardGraphic;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Traits\DeckSessionTrait;
use App\Repository\LibraryRepository;

class ApiController extends AbstractController
{
    use DeckSessionTrait;

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
            ],
            [
                'title' => 'Visa spelstatus',
                'description' => 'Returnerar information om det aktuella spelet, inklusive spelare, kortlek, om spelet är slut och vinnaren. Om inget spel är aktivt, returneras ett felmeddelande.',
                'method' => 'GET',
                'path' => 'game',
                'example' => 'game',
                'response' => json_encode([
                    'players' => [
                        [
                            'name' => 'Player 1',
                            'score' => 15
                        ],
                        [
                            'name' => 'Player 2',
                            'score' => 20
                        ]
                    ],
                    'deck' => [
                        'cards_left' => 30
                    ],
                    'ended' => false,
                    'winner' => null
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            ],
            [
                'title' => 'Visa alla böcker',
                'description' => 'Returnerar alla böcker i biblioteket utan omslagsbilden.',
                'method' => 'GET',
                'path' => 'library/books',
                'example' => 'library/books',
                'response' => json_encode([
                    
                    [
                        "id" => 7,
                        "title" => "The Hired Girl",
                        "isbn" => "9780763678180",
                        "author" => "Laura Amy Schlitz"
                    ],
                    [
                        "id" => 8,
                        "title" => "A Breath of Scandal",
                        "isbn" => "9780505527363",
                        "author" => "Connie Mason"
                    ]
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            ],
            [
                'title' => 'Sök bok med ISBN',
                'description' => 'Returnerar en bok med angiven ISBN.',
                'method' => 'GET',
                'path' => 'library/books/{isbn}',
                'example' => 'library/books/9780763678180',
                'response' => json_encode([
                    "id" => 7,
                    "title" => "The Hired Girl",
                    "isbn" => "9780763678180",
                    "author" => "Laura Amy Schlitz",
                    "img" => "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAAAAAAAD/2wBDAAoHBwkHBgoJCAkLCwoMDxkQDw4ODx4WFxIZJCAmJSMgIyIuKjYp"
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
    public function deck(SessionInterface $session): JsonResponse
    {
        $deck = $this->getDeck($session);

        return $this->json([
            'spades' => $deck->getSortedCardsBySuit('spades'),
            'hearts' => $deck->getSortedCardsBySuit('hearts'),
            'diamonds' => $deck->getSortedCardsBySuit('diamonds'),
            'clubs' => $deck->getSortedCardsBySuit('clubs'),
        ], 200, [], [
            'json_encode_options' => JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        ]);
    }

    #[Route('/api/deck/shuffle', name: 'api_deck_shuffle')]
    public function deckShuffle(SessionInterface $session): JsonResponse
    {
        $deck = $this->getDeck($session);
        $deck->shuffle();
        $this->saveDeck($session, $deck);

        $cards = [];

        foreach ($deck->getCards() as $card) {
            if ($card instanceof CardGraphic) {
                $cards[] = [
                    'name' => $card->getAsString(),
                    'color' => $card->getColor()
                ];
            }
        }

        return $this->json($cards, 200, [], [
            'json_encode_options' => JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        ]);
    }


    #[Route('/api/deck/draw', name: 'api_deck_draw')]
    public function deckDraw(SessionInterface $session): JsonResponse
    {
        $deck = $this->getDeck($session);

        $drawnCards = [];

        if (count($deck->getCards()) > 0) {
            $drawn = $deck->draw();
            if ($drawn instanceof CardGraphic) {
                $drawnCards[] = [
                    'name' => $drawn->getAsString(),
                    'color' => $drawn->getColor(),
                    'value' => $drawn->getValue()
                ];
            }
            $this->saveDeck($session, $deck);
        }

        return $this->json([
            'drawn' => $drawnCards,
            'cards_left' => count($deck->getCards())
        ], 200, [], [
            'json_encode_options' => JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        ]);
    }

    #[Route('/api/deck/draw/{num<\d+>}', name: 'api_deck_draw_number')]
    public function deckDrawNumber(int $num, SessionInterface $session): JsonResponse
    {
        if ($num > 52) {
            throw new \Exception('Cannot draw more cards than the deck contains!');
        }

        $deck = $this->getDeck($session);
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

        $this->saveDeck($session, $deck);

        return $this->json([
            'drawn' => $drawnCards,
            'cards_left' => count($deck->getCards())
        ], 200, [], [
            'json_encode_options' => JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        ]);
    }

    #[Route('/api/game', name: 'api_game')]
    public function game(SessionInterface $session): JsonResponse
    {
        // Check if a game is in session.
        if ($session->get('game')) {
            $game = $session->get('game');

            return $this->json([
                'players' => $game->getPlayers(),
                'deck' => $game->getDeck(),
                'ended' => $game->hasEnded(),
                'winner' => $game->getWinner()
            ], 200, [], [
                'json_encode_options' => JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            ]);
        }

        // If no game is in session, return an error response.
        return $this->json([
            'error' => 'No game in session',
            'message' => 'Please start a game first.'
        ], 400, [], [
            'json_encode_options' => JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        ]);

    }

    #[Route('/api/library/books', name: 'api_library_books')]
    public function libraryBooks(
        LibraryRepository $libraryRepository
    ): JsonResponse
    {
        $books = $libraryRepository->fetchNoCover();

        return $this->json($books, 200, [], [
            'json_encode_options' => JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        ]);
    }

    #[Route('/api/library/books/{isbn<\d+>}', name: 'api_library_books_isbn')]
    public function libraryBooksIsbn(
        LibraryRepository $libraryRepository,
        string $isbn
    ): JsonResponse
    {

        $book = $libraryRepository->findByIsbn($isbn);

        if (!$book) {
            return $this->json([
                'error' => 'Book not found',
                'message' => 'No book found with the given ISBN.'
            ], 404, [], [
                'json_encode_options' => JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            ]);
        }

        return $this->json($book, 200, [], [
            'json_encode_options' => JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        ]);
    }
}

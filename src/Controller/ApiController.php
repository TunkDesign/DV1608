<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
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
            ]
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

        $response->setEncodingOptions( $response->getEncodingOptions() | JSON_PRETTY_PRINT );

        return $response;
    }

}
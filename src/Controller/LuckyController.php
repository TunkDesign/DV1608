<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LuckyController extends AbstractController
{

    #[Route('/lucky', name: 'lucky')]
    public function lucky(): Response
    {
        $fortunes = [
            ['text' => '🍕 Lucky Pizza Slice!', 'img' => 'images/freepizza.gif'],
            ['text' => '🦄 Här har du en hoppande enhörning!', 'img' => 'images/unicorn.gif'],
            ['text' => '🍀 Lycko nummer: ' . random_int(1, 99), 'img' => 'images/clover.gif'],
            ['text' => '🌈 Du hittade regnbågen!', 'img' => 'images/rainbow.gif'],
        ];
    
        $fortune = $fortunes[array_rand($fortunes)];
    
        return $this->render('lucky.html.twig', [
            'luckyThing' => $fortune['text'],
            'imageUrl' => $fortune['img'],
        ]);
    }

}
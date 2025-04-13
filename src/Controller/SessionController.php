<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class SessionController extends AbstractController
{
    #[Route('/session', name: 'session')]
    public function session(
        SessionInterface $session
    ): Response {

        return $this->render('session.html.twig', [
                'session' => $session->all()
        ]);
    }

    #[Route('/session/delete', name: 'session_delete')]
    public function delete(
        SessionInterface $session
    ): Response {
        $session->clear();

        $this->addFlash(
            'success',
            'Nu är sessionen raderad!'
        );

        return $this->redirectToRoute('session');
    }
}

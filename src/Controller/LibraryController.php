<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Library;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\LibraryRepository;

final class LibraryController extends AbstractController
{
    #[Route('/library', name: 'library_home')]
    public function index(): Response
    {
        return $this->render('library/index.html.twig');
    }

    #[Route('/library/create', name: 'library_create')]
    public function library_create(): Response
    {
        return $this->render('library/create.html.twig');
    }

    #[Route('/library/create/post', name: 'library_create_post', methods: ['POST'])]
    public function library_create_post(
        Request $request,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ): Response {
        $entityManager = $doctrine->getManager();

        $library = new Library();
        $library->setTitle(htmlspecialchars($request->request->get('title'), ENT_QUOTES, 'UTF-8'));
        $library->setIsbn(htmlspecialchars($request->request->get('isbn'), ENT_QUOTES, 'UTF-8'));
        $library->setAuthor(htmlspecialchars($request->request->get('author'), ENT_QUOTES, 'UTF-8'));

        $errors = $validator->validate($library);
        if (count($errors) > 0) {
            dump($errors);
            return new Response('Validation failed', 400);
        }

        $img = $request->files->get('cover');
        $imgData = file_get_contents($img->getPathname());
        $base64 = base64_encode($imgData);
        $base64 = 'data:' . $img->getMimeType() . ';base64,' . $base64;
        $library->setImg($base64);

        $entityManager->persist($library);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'Book successfully added to the library!'
        );

        return $this->redirectToRoute('library_view');
    }

    #[Route('/library/edit/{id}', name: 'library_edit')]
    public function library_edit(
        LibraryRepository $libraryRepository,
        int $id
    ): Response {

        $book = $libraryRepository->find($id);

        return $this->render('library/edit.html.twig', [
            'book' => $book,
        ]);
    }
    
    #[Route('/library/edit', name: 'library_edit_post', methods: ['POST'])]
    public function library_edit_post(
        Request $request,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ): Response {
        $entityManager = $doctrine->getManager();
        $library = $entityManager->getRepository(Library::class)->find($request->request->get('id'));
        
        $library->setTitle(htmlspecialchars($request->request->get('title'), ENT_QUOTES, 'UTF-8'));
        $library->setIsbn(htmlspecialchars($request->request->get('isbn'), ENT_QUOTES, 'UTF-8'));
        $library->setAuthor(htmlspecialchars($request->request->get('author'), ENT_QUOTES, 'UTF-8'));

        $errors = $validator->validate($library);
        if (count($errors) > 0) {
            dump($errors);
            return new Response('Validation failed', 400);
        }

        if ($request->files->get('cover') !== null) {
            $img = $request->files->get('cover');
            $imgData = file_get_contents($img->getPathname());
            $base64 = base64_encode($imgData);
            $base64 = 'data:' . $img->getMimeType() . ';base64,' . $base64;
            $library->setImg($base64);
        }
        $entityManager->flush();

        $this->addFlash(
            'success',
            'Book successfully updated!'
        );

        return $this->redirectToRoute('library_edit', [
            'id' => $library->getId(),
        ]);
    }

    #[Route('/library/delete/{id}', name: 'library_delete')]
    public function library_delete(
        LibraryRepository $libraryRepository,
        int $id
    ): Response {
        
        $book = $libraryRepository->find($id);

        return $this->render('library/delete.html.twig', [
            'book' => $book,
        ]);
    }
    
    #[Route('/library/delete', name: 'library_delete_post', methods: ['POST'])]
    public function library_delete_post(
        Request $request,
        ManagerRegistry $doctrine
    ): Response {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Library::class)->find($request->request->get('id'));

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found with id ' . $id
            );
        }

        $entityManager->remove($book);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'Book successfully removed from the library!'
        );

        return $this->redirectToRoute('library_view');
    }

    #[Route('/library/view', name: 'library_view')]
    public function library_view(
        LibraryRepository $libraryRepository
    ): Response {
        $books = $libraryRepository->findAll();

        return $this->render('library/view.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route('/library/view/{id}', name: 'library_view_id')]
    public function library_view_id(
        LibraryRepository $libraryRepository,
        int $id
    ): Response {

        $book = $libraryRepository->find($id);

        return $this->render('library/details.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/library/reset', name: 'library_reset')]
    public function library_reset(): Response
    {
        return $this->redirectToRoute('library_view');
    }
}

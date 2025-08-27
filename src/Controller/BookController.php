<?php

namespace App\Controller;

use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;

final class BookController extends AbstractController
{
    #[Route('/books', name: 'BookList', methods: ['GET'])]
    public function index(BookRepository $bookRepository): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
            
        ]);
    }

        #[Route('/books/{book}', name: 'Book', methods: ['GET'])]
    public function book (int $book, BookRepository $bookRepository): Response
    {
        $book = $bookRepository->find($book);

        if (!$book) {
            return $this->json([
                'message' => 'Livro não encontrado!'
            ]);
        }

        return $this-> json( [
            'data' => $bookRepository->find($book)
        ]);
    }

    #[Route('/books', name: 'BooksCreate', methods: ['POST'])]
    public function create(Request $request, BookRepository $bookRepository): JsonResponse
    {
        $data = $request->request->all();

        $book = new Book();
        $book->setTitle($data['title']);
        $book->setIsbn($data['isbn']);
        $book->setCreatedAt(new \DateTimeImmutable('now', new \DateTimeZone('America/Sao_Paulo')));
        $book->setUpdatedAt(new \DateTimeImmutable('now', new \DateTimeZone('America/Sao_Paulo')));

        $bookRepository->add($book, true);

        return $this->json( [
            'message' => 'Livro criado com sucesso!',
            'data' => $book 
        ],
        201);
    }

    #[Route('/books/{book}', name: 'BooksUpdate', methods: ['PUT', 'PATCH'])]
    public function update(int $book, Request $request, BookRepository $bookRepository, ManagerRegistry $doctrine): JsonResponse
    {
        $data = $request->request->all();
        $book = $bookRepository->find($book);

        $book->setTitle($data['title']);
        $book->setIsbn($data['isbn']);
        $book->setUpdatedAt(new \DateTimeImmutable('now', new \DateTimeZone('America/Sao_Paulo')));

        $doctrine->getManager()->flush();

        return $this->json( [
            'message' => 'Livro atualizado com sucesso!',
            'data' => $book 
        ],
        201);
    }
        
}


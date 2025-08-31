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
    // Lista todos os livrios que estão no banco de dados.
    #[Route('/books', name: 'BookList', methods: ['GET'])]
    public function index(BookRepository $bookRepository): Response
    {
        // Pega todos os livros do repositório(banco de dados).
        $books = $bookRepository->findAll();

        // Retorna os livros em formato JSON.
        return $this->json([
            'books' => $books,
        ]);
    }
        // Mostra um livro específico baseado no ID.
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
        // Cria um novo livro e salva no banco de dados.    
    #[Route('/books', name: 'BooksCreate', methods: ['POST'])]
    public function create(Request $request, BookRepository $bookRepository): JsonResponse
    {   
        // Verifica se o conteúdo da requisição é JSON ou não.
        if($request->headers->get('Content-Type') == 'application/json'){
            $data = $request->toArray();
        } else {
            $data = $request->request->all();
        }

        // Cria um livro e de acordo com os dados recebidos.
        $book = new Book();
        $book->setTitle($data['title']);
        $book->setIsbn($data['isbn']);
        $book->setCreatedAt(new \DateTimeImmutable('now', new \DateTimeZone('America/Sao_Paulo')));
        $book->setUpdatedAt(new \DateTimeImmutable('now', new \DateTimeZone('America/Sao_Paulo')));

        $bookRepository->add($book, true);

        // Retorna uma resposta JSON com o livro criado.
        return $this->json( [
            'message' => 'Livro criado com sucesso!',
            'data' => $book 
        ],
        201);
    }

    // Atualiza/Edita um livro salvo no banco de dados.
    #[Route('/books/{book}', name: 'BooksUpdate', methods: ['PUT', 'PATCH'])]
    public function update(int $book, Request $request, BookRepository $bookRepository, ManagerRegistry $doctrine): JsonResponse
    {   
        // Pega os dados da requisição.
        $data = $request->request->all();
        $book = $bookRepository->find($book);

        // Verifica se o livro existe no banco de dados.
        $book->setTitle($data['title']);
        $book->setIsbn($data['isbn']);
        $book->setUpdatedAt(new \DateTimeImmutable('now', new \DateTimeZone('America/Sao_Paulo')));

        $doctrine->getManager()->flush();

        // Retorna uma resposta JSON com o livro atualizado.
        return $this->json( [
            'message' => 'Livro atualizado com sucesso!',
            'data' => $book 
        ],
        201);
    }
    
    // Deleta o livro pelo seu ID no banco de dados.
    #[Route('/books/{book}', name: 'BooksDelete', methods: ['DELETE'])]
    public function delete(int $book, Request $request, BookRepository $bookRepository): JsonResponse
    {   
        // Verifica se o livro existe no banco de dados.
        $book = $bookRepository->find($book);

        // Se não exitir retorna uma mensagem de erro.
        if (!$book) {
            return $this->json([
                'message' => 'Livro não encontrado!'
            ]);
        }
        // Deleta o livro do banco de dados, e retorna uma mensagem de sucesso.
        return $this->json( [ 
            '$data' => $bookRepository->remove($book, true),
            'message' => 'Livro deletado com sucesso!',
        ]);
    }
}


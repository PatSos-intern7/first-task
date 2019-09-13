<?php
namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Json;
use FOS\RestBundle\Controller\Annotations as Rest;


/**
 * @Route("/api/book")
 */

class ApiBookController extends AbstractController
{
    /**
     *@Rest\Get("/")
     */
    public function getBooks(BookRepository $bookRepository): Response
    {
        $data = $bookRepository->createQueryBuilder('b')
            ->select('b.id','b.title')
            ->getQuery()
            ->getResult();
        return new JsonResponse($data);
    }

    /**
     **@Rest\Get("/{id}")
     */
    public function getOne(BookRepository $bookRepository, int $id): JsonResponse
    {
        $book= $bookRepository->find($id);
        if(!$book){
            return new JsonResponse('Resource not found.',404 );
        }
        return new JsonResponse($book->jsonSerialize());
    }
}

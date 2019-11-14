<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use App\Service\FileUploader;
use App\Service\LibraryLogger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/library/book")
 */
class BookController extends AbstractController
{
    /**
     * @var LibraryLogger
     */
    private $libraryLogger;

    private $session;

    public function __construct(LibraryLogger $libraryLogger)
    {
        $this->libraryLogger = $libraryLogger;
        $this->session = new Session();
    }

    /**
     * @Route("/", name="book_index", methods={"GET"})
     */
    public function index(BookRepository $bookRepository): Response
    {
        return $this->render('book/index.html.twig', [
            'books' => $bookRepository->findAll(),
        ]);
    }
    /**
     * @Route("/randomBook", name="random_book", methods={"GET","POST"})
     */
    public function randomBook(BookRepository $bookRepository)
    {
        $bookIndex = $bookRepository->createQueryBuilder('book');
        $result = $bookIndex->getQuery()->getResult();
        $randomIndex = array_rand($result);
        $id = $result[$randomIndex]->getId();
        $this->libraryLogger->makeEntry($id);
        $this->session->replace(['lastRandomBook'=>$id]);
        return $this->redirectToRoute('book_show',['id'=>$id]);
    }

    /**
     * @Route("/new", name="book_new", methods={"GET","POST"})
     */
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form['CoverImage']->getData();
            if($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $book->setCoverImage($imageFileName);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();
            $this->addFlash('success',$this->getFlashContent($book, 'Created'));

            return $this->redirectToRoute('book_index');
        }

        return $this->render('book/new.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="book_show", methods={"GET"})
     */
    public function show(Book $book): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="book_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Book $book, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form['CoverImage']->getData();
            if($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $book->setCoverImage($imageFileName);
            }

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('info',$this->getFlashContent($book, 'Edited'));

            return $this->redirectToRoute('book_index');
        }

        return $this->render('book/edit.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="book_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Book $book, FileUploader $fileUploader): Response
    {
        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->request->get('_token'))) {
            $fileUploader->removeFile($book->getCoverImage());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($book);
            $entityManager->flush();

            $this->addFlash('warning',$this->getFlashContent($book, 'Deleted'));
        }

        return $this->redirectToRoute('book_index');
    }

    public function getFlashContent(Book $book, string $status):string
    {
        return $status.' book with title:'.$book->getTitle()
            .' and author: '.$book->getAuthor()->getName().' '.$book->getAuthor()->getSurname();
    }
}

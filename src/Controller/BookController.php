<?php

namespace App\Controller;
use App\Form\AuthorType;
use App\Entity\Book;
use App\Entity\Author;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\BookType;
use App\Controller\AuthorController;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;



class BookController extends AbstractController
{
    
    
    
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/affiche_book', name: 'app_affiche_book')]
    public function Affiche(BookRepository $repository)
    {
        $book=$repository->findAll();
        return $this->render('book/affiche_book.html.twig', ['book'=>$book]);
    }


    #[Route('/add_book', name: 'app_add_book')]
    public function addBook(Request $request)
    {
        $book = new Book(); 
        $form = $this->createForm(BookType::class, $book); // Create a form type for your Book entity
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();
            $this->addFlash('success', 'Book added successfully.');
            return $this->redirectToRoute('app_affiche_book'); // Redirect to a book listing page
        }
        return $this->render('book/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/editBook/{ref}', name: 'app_edit_book')]
public function editBook(int $ref, Request $request, BookRepository $repository): Response
{
    $book = $repository->find($ref);

    if ($book === null) {
        throw $this->createNotFoundException('Book not found.');
    }

    $form = $this->createForm(BookType::class, $book);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return $this->redirectToRoute('app_affiche_book'); // Redirect to the author list page after editing.
    }

    return $this->render('book/edit_book.html.twig', [
        'form' => $form->createView(), // Pass the 'form' variable to the template
        'book' => $book,
    ]);
}
#[Route('/deleteBook/{ref}', name: 'app_delete_book')]
public function delete(int $ref, BookRepository $repository): Response
{
    $book = $repository->find($ref);

    if ($book) {
        // Delete the author from the database.
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($book);
        $entityManager->flush();
    }

    return $this->redirectToRoute('app_affiche_book');
}

#[Route('/ShowBook/{ref}', name: 'app_show_book')]

    public function showBook($ref, BookRepository $repository)
    {
        $book = $repository->find($ref);
        if (!$book) {
            return $this->redirectToRoute('app_affiche_book');
        }

        return $this->render('book/show_book.html.twig', ['b' => $book]);

    
    }

}

/*
public function addBook(Request $request, EntityManagerInterface $entityManager): Response
{
    
    $em = $this->getDoctrine()->getManager();
    $authorRepository = $em->getRepository(Author::class);
    $authors = $authorRepository->findAll(); 
    dump($authors);
    $book = new Book();
    $form = $this->createForm(BookType::class, $book, [
        'entity_manager' => $entityManager,
    ]);

    $form->handleRequest($request);
    $book->setRef('your_manual_value_here');
    $form->handleRequest($request);
    foreach ($authors as $author) {
        $authorChoices[$author->getUsername()] = $author->getUsername();
    }

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($book);
        $entityManager->flush();

        return $this->redirectToRoute('app_affiche_book');
    }

    return $this->render('book/add.html.twig', [
        'form' => $form->createView(),
    ]);
}
*/
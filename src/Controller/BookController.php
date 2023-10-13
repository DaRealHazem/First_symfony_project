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
    public function addBook(Request $request, EntityManagerInterface $entityManager): Response
{
    
    $em = $this->getDoctrine()->getManager();
$authorRepository = $em->getRepository(Author::class);
$authors = $authorRepository->findAll(); 
dump($authors);
    $book = new Book();
    $form = $this->createForm(BookType::class, $book, [
        'entity_manager' => $entityManager, // Pass the EntityManager
    ]);

    $form->handleRequest($request);
    foreach ($authors as $author) {
        $authorChoices[$author->getUsername()] = $author->getUsername();
    }

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($book); // Assuming $book is the instance of your Book entity
        $entityManager->flush();
    
        return $this->redirectToRoute('app_affiche_book');
    }

    return $this->render('book/add.html.twig', [
        'form' => $form->createView(),
    ]);
}
}

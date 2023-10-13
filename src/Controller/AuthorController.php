<?php

namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Test\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\AuthorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;



use Symfony\Component\HttpFoundation\Request;

class AuthorController extends AbstractController
{
    
    private $authors; // Declare $authors as a class property

    public function __construct()
    {
        // Define the $authors array in the constructor
        $this->authors = array(
            array('id' => 1, 'picture' => '/images/victor_hugo.jpg', 'username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com', 'nb_books' => 100),
            array('id' => 2, 'picture' => '/images/william_shakespeare.jpg', 'username' => 'William Shakespeare', 'email' => 'william.shakespeare@gmail.com', 'nb_books' => 200),
            array('id' => 3, 'picture' => '/images/taha_hussein.jpg', 'username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),
        );
    }

    #[Route('/showauthor/{id}', name: 'showauthor')]
    public function showService($id, AuthorRepository $authorRepo): Response
    {
        $author = $authorRepo->find($id);
        return $this->render('author/detailAuthor.html.twig', [
            'author' => $author,
        ]);
    }
    #[Route('/showAuthor/{author_name}', name: 'show_author')]
    public function showAuthor($author_name): Response
    {
        return $this->render('author/showAuthor.html.twig', [
            'twig_author_name' => $author_name,
        ]);
    }
    #[Route('/list', name: 'list')]
    public function list(AuthorRepository $authorRepo): Response{

        $author = $authorRepo->findAll();
        return $this->render('author/list.html.twig', [ 'authors' => $this->authors
        ]);
    }

    #[Route('/authorDetails/{id}', name: 'author_details')]
    public function authorDetails($id){
            // Find the author with the given ID in the $authors array
            $author = null;
            foreach ($this->authors as $a) {
                if ($a['id'] == $id) {
                    $author = $a;
                    break;
                }
            }
    
            if (!$author) {
                // Handle the case where the author with the given ID is not found
                throw $this->createNotFoundException('Author not found');
            }
    
            // Render a template to display author details, passing the author data to the template
            return $this->render('author/showAuthor.html.twig', [
                'twig_author' => $author,
            ]);
    }


    /*
    #[Route('/deleteAuthor/{id}', name: 'app_delete_author')]
    public function deleteAuthor($id, ManagerRegistry $doctrine) : Response
    {
        $em=$doctrine->getManager();
        $repo=$doctrine->getRepository(Author::class);
        $author=$repo->find($id);
        $em->remove($author);
        $em->flush();
        return $this->redirectToRoute('list_author');
    }
    */
    #[Route('/affiche', name: 'app_affiche')]
    public function Affiche(AuthorRepository $repository)
    {
        $author=$repository->findAll();
        return $this->render('author/affiche.html.twig', ['author'=>$author]);
    }

  /*  #[Route('/ajout', name: 'app_ajout')]
public function addStatique(AuthorRepository $repository) : Response {
    $author1 = new Author();
    $author1->setUsername("Hazem"); // Use 'set' method to set properties
    $author1->setEmail("test@gmail.com");

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($author1);
    $entityManager->flush();

    return $this->redirectToRoute('app_affiche');
}
*/
#[Route('/ajout', name: 'app_ajout')]

public function addStatique(Request $request, ManagerRegistry $doctrine): Response {
    $author = new Author();
    $form = $this->createForm(AuthorType::class, $author);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $doctrine->getManager(); // Access the entity manager via the ManagerRegistry service
        $entityManager->persist($author);
        $entityManager->flush();

        return $this->redirectToRoute('app_affiche');
    }

    return $this->render('form/form.html.twig', [
        'form' => $form->createView(),
    ]);
}
#[Route('/editAuthor/{id}', name: 'app_edit_author')]
public function edit(int $id, Request $request, AuthorRepository $repository): Response
{
    $author = $repository->find($id);

    if (!$author) {
        // Handle the case when the author is not found (e.g., show an error message or redirect).
    }

    $form = $this->createForm(AuthorType::class, $author);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return $this->redirectToRoute('app_affiche'); // Redirect to the author list page after editing.
    }

    return $this->render('Form/edit_author.html.twig', [
        'form' => $form->createView(), // Pass the 'form' variable to the template
    ]);
}


#[Route('/deleteAuthor/{id}', name: 'app_delete_author')]
public function delete(int $id, AuthorRepository $repository): Response
{
    $author = $repository->find($id);

    if ($author) {
        // Delete the author from the database.
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($author);
        $entityManager->flush();
    }

    return $this->redirectToRoute('app_affiche');
}


}
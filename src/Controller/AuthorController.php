<?php

namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route('/showauthor/{username}', name: 'show_author')]
    public function showAuthor($username)
    {
        return $this->render("author/show.html.twig"
            ,array('nameAuthor'=>$username));
    }

    #[Route('/list', name: 'list_author')]
    public function listAuthors()
    {
        $authors = array(
            array('id' => 1, 'username' => ' Victor Hugo','email'=> 'victor.hugo@gmail.com', 'nb_books'=> 100),
            array ('id' => 2, 'username' => 'William Shakespeare','email'=>
                'william.shakespeare@gmail.com','nb_books' => 200),
            array('id' => 3, 'username' => ' Taha Hussein','email'=> 'taha.hussein@gmail.com','nb_books' => 300),
        );
        return $this->render("author/list.html.twig",
            array('tabAuthors'=>$authors));
    }

    #[Route('/listAuthor', name: 'authors')]
    public function list(AuthorRepository $repository)
    {
        $authors= $repository->findAll();

        return $this->render("author/listAuthors.html.twig",
            array('tabAuthors'=>$authors));
    }

    #[Route('/addAuthor', name: 'addAuthor')]
    public function addAuthor(ManagerRegistry $managerRegistry)
    {
       $author= new Author();
       $author->setEmail("sami@gmail.com");
       $author->setUsername("sami");
       #$em= $this->getDoctrine()->getManager();
        $em= $managerRegistry->getManager();
        $em->persist($author);
        $em->flush();
        return $this->redirectToRoute("authors");

    }

    #[Route('/update/{id}', name: 'updateAuthor')]
    public function updateAuthor($id,AuthorRepository $repository,ManagerRegistry $managerRegistry)
    {
        $author= $repository->find($id);
        $author->setEmail("sami@gmail.com");
        $author->setUsername("sami");
        $em= $managerRegistry->getManager();
        $em->flush();
        return $this->redirectToRoute("list_authors");
    }

    #[Route('/remove/{id}', name: 'remove')]

    public function deleteAuthor(ManagerRegistry $managerRegistry,$id,AuthorRepository $repository)
    {
        $author= $repository->find($id);
        $em= $managerRegistry->getManager();
        $em->remove($author);
        $em->flush();
        return $this->redirectToRoute("list_authors");

    }
}
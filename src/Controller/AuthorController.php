<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;

use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


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


    #[Route('/listAuthor', name: 'authors')]
    public function list(AuthorRepository $repository)
    {
        $authors= $repository->findAll();

        return $this->render("author/listAuthors.html.twig",
            array('tabAuthors'=>$authors));
    }

    #[Route('/addAuthor', name: 'addAuthor')]
    public function addAuthor(Request $request,ManagerRegistry $managerRegistry)
    {
       $author= new Author();
       $form= $this->createForm(AuthorType::class,$author);
       $form->handleRequest($request);
       if($form->isSubmitted()){
        $em= $managerRegistry->getManager();
        $em->persist($author);
       $em->flush();
       return $this->redirectToRoute("list_authors");
       }
      /* $author->setEmail("ahmed@gmail.com");
       $author->setUsername("ahmed");
       #$em= $this->getDoctrine()->getManager();
        $em= $managerRegistry->getManager();
        $em->persist($author);
        $em->flush();
       return $this->redirectToRoute("list_authors");*/
    
        return $this->renderForm("author/add.html.twig"
            ,array('formulaireAuthor'=>$form));
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
<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Form\ChercherType;
use App\Form\NombreType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
    #[Route('/addbook', name: 'addbook')]
    public function addbook(ManagerRegistry $manag, Request $req): Response
    {
        $em = $manag->getManager();
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute("showbook");
        }
        return $this->renderForm('book/addbook.html.twig', [
            'f' => $form,
        ]);
    }
    #[Route('/showbook', name: 'showbook')]
    public function showdbook(BookRepository $rep): Response
    {
        //$book = $rep->findAll();
        $book = $rep->triauthor();

        return $this->render('book/showbook.html.twig', [
            'book' => $book
        ]);
    }

    #[Route('/showbookannee', name: 'showbookannee')]
    public function showbookannee(BookRepository $rep): Response
    {
        //$book = $rep->findAll();
        $book = $rep->showannee();

        return $this->render('book/showbookannee.html.twig', [
            'book' => $book
        ]);
    }

    #[Route('/updatecategory', name: 'updatecategory')]
    public function updatecategory(BookRepository $rep): Response
    {
        $rep->updateCategorie();
        $book = $rep->findAll();

        //var_dump($rep) . die();
        return $this->render('book/updatecategory.html.twig', [
            'book' => $book
        ]);
    }

    #[Route('/search', name: 'search')]
    public function search(BookRepository $rep, Request $req): Response
    {
        $book = $rep->findAll();
        $form = $this->createForm(ChercherType::class);
        $form->handleRequest($req);

        $data = $form->get('ref')->getData();
        $books = $rep->chercherrefbook($data);

        return $this->renderForm('book/search.html.twig', [
            'f' => $form,
            'books' => $books,

        ]);
    }

    #[Route('/sommeScienceFinction', name: 'sommeScienceFinction')]
    public function sommeScienceFinction(BookRepository $rep)
    {
        $result = $rep->sommeScienceFinction();
        $book = $rep->findAll();

        return $this->render('book/updatecategory.html.twig', [
            'result' => $result,
            'book' => $book
        ]);
    }

    #[Route('/between', name: 'between')]
    public function between(BookRepository $rep)
    {
        $book = $rep->showbetween();

        return $this->render('book/between.html.twig', [
            'book' => $book
        ]);
    }


    #[Route('/findauthor', name: 'findauthor')]
    public function findauthor(BookRepository $rep, Request $req): Response
    {
        $book = $rep->findAll();
        $form = $this->createForm(NombreType::class);
        $form->handleRequest($req);

        $minb = $form->get('minb')->getData();
        $maxb = $form->get('maxb')->getData();
        $books = $rep->findauthor($minb, $maxb);

        return $this->renderForm('book/findauthor.html.twig', [
            'f' => $form,
            'books' => $books,

        ]);
    }


    #[Route('/editbook/{ref}', name: 'editbook')]
    public function editbook($ref, BookRepository $rep, ManagerRegistry $managerRegistry, Request $req): Response
    {
        // var_dump($id) . die();
        $em = $managerRegistry->getManager();
        $data = $rep->find($ref);
        //var_dump($dataid) . die();
        $form = $this->createForm(BookType::class, $data);
        $form->handleRequest($req);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($data);
            $em->flush();
            return $this->redirectToRoute('showbook');
        }

        return $this->renderForm('book/editbook.html.twig', [
            'f' => $form
        ]);
    }

    #[Route('/deletbook/{ref}', name: 'deletbook')]
    public function deletbook($ref, ManagerRegistry $managerRegistry, BookRepository $repo): Response
    {
        $em = $managerRegistry->getManager();
        $book = $repo->find($ref);
        $em->remove($book);
        $em->flush();
        return $this->redirectToRoute('showbook');
    }


    #[Route('/deletezerobook', name: 'deletezerobook')]
    public function deletezerobook(BookRepository $repo): Response
    {
        $repo->deleteZeroBook();
        return $this->redirectToRoute('showbook');
    }
}

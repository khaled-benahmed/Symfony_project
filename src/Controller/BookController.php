<?php
namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;

use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

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

    #[Route('/showbook/{ref}', name: 'show_book')]
    public function showBook($ref)
    {
        return $this->render("book/show.html.twig"
            ,array('nameBook'=>$ref));
    }
    #[Route('/listBook', name: 'books')]
    public function list(BookRepository $repository)
    {
        $books= $repository->findAll();

        return $this->render("book/listBooks.html.twig",
            array('tabBooks'=>$books));
    }

    #[Route('/addBook', name: 'addBook')]
    public function addBook(Request $request,ManagerRegistry $managerRegistry)
    {
        $book= new Book();
        $form= $this->createForm(BookType::class,$book);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $book->setPublished(true);
            $em= $managerRegistry->getManager();
            $em->persist($book);
            $em->flush();
            return new Response("Done!");
        }
        return $this->renderForm('book/add.html.twig',array("formulaireBook"=>$form));
    }
    #[Route('/updateBook/{ref}', name: 'updateBook')]
    public function updateBook($ref,BookRepository $repository,ManagerRegistry $managerRegistry)
    {
        $book= $repository->find($ref);
        $book->setTitle("My Book Updated");
        $em= $managerRegistry->getManager();
        $em->flush();
        return $this->redirectToRoute("books");
    }
    #[Route('/editBook/{ref}', name: 'editBook')]
public function editBook(Request $request, ManagerRegistry $managerRegistry, $ref)
{
    $em = $managerRegistry->getManager();
    $book = $em->getRepository(Book::class)->find($ref);
    $form = $this->createForm(BookType::class, $book);
    $form->handleRequest($request);

    if ($form->isSubmitted() ) {
        $em->flush();
        return $this->redirectToRoute('books'); // Redirect to a book listing page
    }

    return $this->renderForm('book/add.html.twig', ['formulaireBook' => $form]);
}
#[Route('/deleteBook/{ref}', name: 'deleteBook')]
public function deleteBook(ManagerRegistry $managerRegistry, $ref)
{
    $em = $managerRegistry->getManager();
    $book = $em->getRepository(Book::class)->find($ref);
    $em->remove($book);
    $em->flush();

    return $this->redirectToRoute('books'); // Redirect to a book listing page
}


}



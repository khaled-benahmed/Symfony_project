<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function chercherrefbook($ref)
    {
        return $this->createQueryBuilder('b')
            ->where('b.ref =:ref')
            ->setParameter('ref', $ref)
            ->getQuery()
            ->getResult();
    }

    public function triauthor()
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.author', "Asc")
            ->getQuery()
            ->getResult();
    }


    public function showannee()
    {
        return $this->createQueryBuilder('b')
            ->select("b")
            ->andWhere("b.nbrbook > 35  ")
            ->andWhere("b.publicationdate < '2023-01-01'")
            ->getQuery()
            ->getResult();
    }

    public function updateCategorie()
    {
        return $this->createQueryBuilder('b')
            ->update('App\Entity\Book', 'b')
            ->set('b.categorie', ':newcategorie')
            ->where('b.author = :username')
            ->setParameters(['newcategorie' => 'romantic', 'username' => 'William Shakespeare'])
            ->getQuery()
            ->getResult();
    }


    public function sommeScienceFinction()
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT SUM(b.nbrbook) FROM App\Entity\Book b WHERE b.categorie = :categorie")
            ->setParameter('categorie', 'Science Fiction');

        return $query->getSingleScalarResult();
    }

    public function showbetween()
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery("SELECT b FROM App\Entity\Book b WHERE b.publicationdate BETWEEN :datemin AND :datemax ")
            ->setParameters(['datemin' => '2014-01-01', 'datemax' => '2018-12-31']);

        return $query->getResult();
    }



    public function findauthor($minb, $maxb)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery("SELECT b FROM App\Entity\Book b WHERE b.nbrbook >= :minb AND b.nbrbook <= :maxb ");
        $query->setParameters(['minb' => $minb, 'maxb' => $maxb]);

        return $query->getResult();;
    }





    public function deleteZeroBook()
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery("DELETE FROM App\Entity\Book b WHERE b.nbrbook = 0");
        $query->execute();
    }




















    //    /**
    //     * @return Book[] Returns an array of Book objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Book
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

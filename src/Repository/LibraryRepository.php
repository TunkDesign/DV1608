<?php

namespace App\Repository;

use App\Entity\Library;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Library>
 */
class LibraryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Library::class);
    }

    /**
     * Fetch all books but without the image.
     * 
     * @return [][] Returns an array of arrays (i.e. a raw data set)
     */
    public function fetchNoCover(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT id, title, isbn, author FROM library
        ';

        $resultSet = $conn->executeQuery($sql);

        return $resultSet->fetchAllAssociative();
    }

    /**
     * Fetch book by ISBN.
     * 
     * @return Library[] Returns an array of Library objects
     */
    public function findByIsbn($isbn): ?Library
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.isbn = :isbn')
            ->setParameter('isbn', $isbn)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}

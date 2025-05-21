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
    * @return array<int, array{id: int, title: string, isbn: string, author: string}>
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
     * @param string $isbn
     * @return Library|null Returns a Library object or null if not found
     */
    public function findByIsbn(string $isbn): ?Library
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.isbn = :isbn')
            ->setParameter('isbn', $isbn)
            ->getQuery()
            ->getOneOrNullResult();
    }
}

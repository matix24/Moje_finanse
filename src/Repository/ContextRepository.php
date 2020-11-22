<?php

namespace App\Repository;

use App\Entity\Context;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Context|null find($id, $lockMode = null, $lockVersion = null)
 * @method Context|null findOneBy(array $criteria, array $orderBy = null)
 * @method Context[]    findAll()
 * @method Context[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContextRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Context::class);
    }

    /**
     * Paginator dla DataTable
     * @param User $user
     * @param int $startFromElement
     * @param int $itemPerPage
     * @param string|null $searchValue
     * @param array|null $filterBy
     * @param array|null $orderBy
     * @param array|null $columnsForOrder
     * @return Paginator
     */
    public function fetchForPaginator(User $user, int $startFromElement, int $itemPerPage, ?string $searchValue = null, ?array $filterBy = array(), ?array $orderBy = array(), ?array $columnsForOrder = array()): Paginator
    {
        # buduje query
        $queryBuilder = $this->createQueryBuilder('c');
        $queryBuilder->where('c.user = :user')->setParameter('user', $user->getId());

        # wyszukiwarka
        if (!is_null($searchValue) && $searchValue != '') {
            $searchValue = trim($searchValue);
            $queryBuilder->andWhere('c.name LIKE :searchTerm')->setParameter('searchTerm', '%' . $searchValue . '%');
        }

        # sortowanie
        if (!is_null($orderBy) && !empty($orderBy)) {
            $chooseColumn = $columnsForOrder[$orderBy[0]['column']];
            $queryBuilder->orderBy($chooseColumn['name'], $orderBy[0]['dir']);
        }

        $paginator = new Paginator($queryBuilder->getQuery());
        $paginatorQuery = $paginator->getQuery();
        $paginatorQuery->setFirstResult($startFromElement);
        if ($itemPerPage > 0) {
            $paginatorQuery->setMaxResults($itemPerPage);
        }

        return $paginator;
    } // end fetchForPaginator

    public function countContextForUser(User $user)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->select($qb->expr()->count('c'))
            ->where('c.user = :user')
            ->andWhere('c.is_archive = 0')
            ->setParameter('user', $user->getId());
        $query = $qb->getQuery();
//dump($query); die;
        return $query->getSingleScalarResult();
    } // end countContextForUser

    // /**
    //  * @return Context[] Returns an array of Context objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Context
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

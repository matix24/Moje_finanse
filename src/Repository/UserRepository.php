<?php

namespace App\Repository;

use App\Entity\User;
use App\Security\AuthRole;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     * @param UserInterface $user
     * @param string $newEncodedPassword
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * Paginator dla DataTable
     * @param int $page
     * @param int $itemPerPage
     * @param string|null $searchValue
     * @param array|null $filterBy
     * @param array|null $orderBy
     * @param array|null $columnsForOrder
     * @return Paginator
     */
    public function fetchForPaginator(int $page, int $itemPerPage, ?string $searchValue = null, ?array $filterBy = array(), ?array $orderBy = array(), ?array $columnsForOrder = array()): Paginator
    {

        $queryBuilder = $this->createQueryBuilder('u');

        # wyszukiwarka
        if (!is_null($searchValue) && $searchValue != '') {
            $searchValue = trim($searchValue);
            $queryBuilder->andWhere('u.email LIKE :searchTerm OR u.name LIKE :searchTerm OR u.surname LIKE :searchTerm OR CONCAT(u.name,' . "' '" . ', u.surname) LIKE :searchTerm')->setParameter('searchTerm', '%' . $searchValue . '%');
        }

        # sortowanie
        if (!is_null($orderBy) && !empty($orderBy)) {
            $chooseColumn = $columnsForOrder[$orderBy[0]['column']];
            $queryBuilder->orderBy($chooseColumn['name'], $orderBy[0]['dir']);
        }

        $paginator = new Paginator($queryBuilder->getQuery());
        $paginatorQuery = $paginator->getQuery();
        $paginatorQuery->setFirstResult($page);
        if ($itemPerPage > 0) {
            $paginatorQuery->setMaxResults($itemPerPage);
        }

        return $paginator;
    } // end fetchForPaginator

    /**
     * Pobieram listę klientów przypisanych do danego partnera
     * @param int $page
     * @param int $itemPerPage
     * @param int $idPartner
     * @param string|null $searchValue
     * @param array|null $filterBy
     * @param array|null $orderBy
     * @param array|null $columnsForOrder
     * @return Paginator
     */
    public function fetchAllUserForPartnerForPaginator(int $page, int $itemPerPage, int $idPartner, ?string $searchValue = null, ?array $filterBy = array(), ?array $orderBy = array(), ?array $columnsForOrder = array())
    {
        $queryBuilder = $this->createQueryBuilder('u');
        $queryBuilder->where('u.partner = :idPartner')->setParameter('idPartner', $idPartner);
        $queryBuilder->andWhere('u.roles LIKE :role')->setParameter('role', '%"' . AuthRole::ROLE_CUSTOMER . '"%');

        # wyszukiwarka
        if (!is_null($searchValue) && $searchValue != '') {
            $searchValue = trim($searchValue);
            $queryBuilder->andWhere('u.email LIKE :searchTerm OR u.name LIKE :searchTerm OR u.surname LIKE :searchTerm OR CONCAT(u.name,' . "' '" . ', u.surname) LIKE :searchTerm')->setParameter('searchTerm', '%' . $searchValue . '%');
        }

        # sortowanie
        if (!is_null($orderBy) && !empty($orderBy)) {
            $chooseColumn = $columnsForOrder[$orderBy[0]['column']];
            $queryBuilder->orderBy($chooseColumn['name'], $orderBy[0]['dir']);
        }

        $paginator = new Paginator($queryBuilder->getQuery());
        $paginatorQuery = $paginator->getQuery();
        $paginatorQuery->setFirstResult($page);
        if ($itemPerPage > 0) {
            $paginatorQuery->setMaxResults($itemPerPage);
        }

        return $paginator;
    } // end fetchAllUserForPartnerForPaginator

    /**
     * Wyszukuję użytkowników po danej roli w systemie
     *
     * @param string $role
     * @return array
     */
    public function findByRole(string $role): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.roles LIKE :val')
            ->setParameter('val', '%"' . $role . '"%')
            ->getQuery()
            ->getResult();
    } // end findByRole

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}// end class

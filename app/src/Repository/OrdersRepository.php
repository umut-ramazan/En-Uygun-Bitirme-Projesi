<?php

namespace App\Repository;

use App\Entity\Orders;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use mysql_xdevapi\Exception;
use Psr\Log\LoggerInterface;

/**
 * @extends ServiceEntityRepository<Orders>
 *
 * @method Orders|null find($id, $lockMode = null, $lockVersion = null)
 * @method Orders|null findOneBy(array $criteria, array $orderBy = null)
 * @method Orders[]    findAll()
 * @method Orders[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdersRepository extends ServiceEntityRepository
{
    private LoggerInterface $logger;

    public function __construct(ManagerRegistry $registry, LoggerInterface $logger)
    {
        parent::__construct($registry, Orders::class);
        $this->logger = $logger;
    }

    public function add(Orders $entity, bool $flush = false): bool
    {
        try {
            $this->getEntityManager()->persist($entity);
            if ($flush) {

                $this->getEntityManager()->flush();

                $this->logger->info('Orders Adding .');

                return true;
            }
        } catch (Exception $exception) {

            $this->logger->error('Order Adding Error :' . $exception->getMessage());

            return false;
        }
        return false;
    }

    public function remove(Orders $entity, bool $flush = false): void
    {
        try {
            $this->getEntityManager()->remove($entity);

            if ($flush) {
                $this->getEntityManager()->remove($entity);
                $this->logger->info('Order Remove ');
            }

        } catch (Exception $exception) {
            $this->logger->error('Order Remove Error :' . $exception->getMessage());
        }

    }

    public function update(Orders $entity, bool $flush = false): bool
    {
        try {
            $this->getEntityManager()->persist($entity);

            if ($flush) {
                $this->getEntityManager()->flush();
                $this->logger->info('Order Update');
                return true;
            }

        } catch (Exception $exception) {

            $this->logger->error('Order Update Eror: ' . $exception->getMessage());

            return false;
        }

        return false;
    }

    /**
     * @return Orders[] Returns an array of Orders objects
     */
    public function findByUserOrders($value): array
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.shoppingCartId', 's')
            ->andWhere('o.userId = :userId')
            ->setParameter('userId', $value)
            ->select('o', 's')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Orders[] Returns an array of Orders objects
     */
    public function findByAllOrders(): array
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.shoppingCartId', 's')
            ->select('o', 's')
            ->getQuery()
            ->getResult();
    }

//    public function findOneBySomeField($value): ?Orders
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

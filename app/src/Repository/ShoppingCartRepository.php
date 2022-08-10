<?php

namespace App\Repository;

use App\Entity\ShoppingCart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use mysql_xdevapi\Exception;
use Psr\Log\LoggerInterface;

/**
 * @extends ServiceEntityRepository<ShoppingCart>
 *
 * @method ShoppingCart|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShoppingCart|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShoppingCart[]    findAll()
 * @method ShoppingCart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShoppingCartRepository extends ServiceEntityRepository
{
    private LoggerInterface $logger;

    public function __construct(ManagerRegistry $registry, LoggerInterface $logger)
    {
        parent::__construct($registry, ShoppingCart::class);
        $this->logger = $logger;
    }

    public function add(ShoppingCart $entity, bool $flush = false): bool
    {
        try {
            $this->getEntityManager()->persist($entity);
            if ($flush) {

                $this->getEntityManager()->flush();
                $this->logger->info('Shopping Adding');

                return true;
            }
        } catch (Exception $exception) {

            $this->logger->error('Shopping Adding Error :' . $exception->getMessage());

            return false;
        }
        return false;
    }

    public function remove(ShoppingCart $entity, bool $flush = false): bool
    {
        try {
            $this->getEntityManager()->remove($entity);

            if ($flush) {

                $this->getEntityManager()->flush();
                $this->logger->info('Shopping Remove');

                return true;
            }
        } catch (Exception $exception) {

            $this->logger->error('Shopping Remove Error: ' . $exception->getMessage());

            return false;
        }
        return false;
    }



//    /**
//     * @return ShoppingCart[] Returns an array of ShoppingCart objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ShoppingCart
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

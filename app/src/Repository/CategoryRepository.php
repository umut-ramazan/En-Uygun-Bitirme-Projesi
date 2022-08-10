<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use mysql_xdevapi\Exception;
use Psr\Log\LoggerInterface;
use function Symfony\Component\Translation\t;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    private LoggerInterface $logger;

    public function __construct(ManagerRegistry $registry, LoggerInterface $logger)
    {
        parent::__construct($registry, Category::class);
        $this->logger = $logger;
    }

    public function add(Category $entity): string
    {
        try {
            $this->getEntityManager()->persist($entity);
            $this->getEntityManager()->flush();

            $this->logger->info('Category added.');

            return true;
        } catch (Exception $exception) {
            $this->logger->error('Category Adding Error: ' . $exception->getMessage());
            return $exception->getMessage();
        }

    }

    public function update(Category $entity): string
    {
        try {
            $this->getEntityManager()->persist($entity);
            $this->getEntityManager()->flush();

            $this->logger->info('Category Updated.');

            return true;
        } catch (Exception $exception) {

            $this->logger->error('Category Update Error: ' . $exception->getMessage());

            return $exception->getMessage();
        }

    }

    public function remove(Category $entity): bool
    {
        try {
            $this->getEntityManager()->remove($entity);
            $this->getEntityManager()->flush();

            $this->logger->info('Category Deleted.');

            return true;
        } catch (Exception $exception) {

            $this->logger->error('Category Deletion Error:' . $exception->getMessage());

            return $exception->getMessage();
        }
    }

    /**
     * @return array
     */
    public function categoryProductCountFind(): array
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.products', 'p')
            ->select('c', 'count(p)')
            ->groupBy('c')
            ->getQuery()->getResult();
    }



//    public function findOneBySomeField($value): ?Category
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

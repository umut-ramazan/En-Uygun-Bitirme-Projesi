<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use mysql_xdevapi\Exception;
use Psr\Log\LoggerInterface;


/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    private LoggerInterface $logger;

    public function __construct(ManagerRegistry $registry, LoggerInterface $logger)
    {
        parent::__construct($registry, Product::class);
        $this->logger = $logger;
    }

    public function add(Product $entity, bool $flush = false): bool
    {
        try {
            $this->getEntityManager()->persist($entity);

            if ($flush) {

                $this->getEntityManager()->flush();
                $this->logger->info('Product Adding ');

                return true;
            }
        } catch (Exception $exception) {

            $this->logger->error('Product Adding Error: ' . $exception->getMessage());

            return false;
        }
        return false;
    }

    public function remove(Product $entity, bool $flush = false): bool
    {
        try {

            $this->getEntityManager()->remove($entity);

            if ($flush) {

                $this->getEntityManager()->flush();
                $this->logger->info('Product Remove');

                return true;
            }
        } catch (Exception $exception) {

            $this->logger->error('Product Remove Error: ' . $exception->getMessage());

            return false;

        }
        return false;
    }

    public function update(Product $entity, bool $flush = false): bool
    {
        try {
            $this->getEntityManager()->persist($entity);
            if ($flush) {

                $this->getEntityManager()->flush();
                $this->logger->info('Product Update');

                return true;

            }
        } catch (Exception $exception) {

            $this->logger->error('Product Update Error: ' . $exception->getMessage());

            return false;

        }
        return false;
    }


    /**
     * @return array
     */
    public function productsFindByAll(): array
    {
        // returns an array of Product objects
        return $this->createQueryBuilder('p')
            ->leftJoin('p.categories', 'c')
            ->select('p', 'c')
            ->getQuery()->getResult();
    }

    /**
     * @param $categoryName
     * @return array
     */
    public function productsFindByCategory($categoryName): array
    {
        // returns an array of Product objects
        return $this->createQueryBuilder('p')
            ->leftJoin('p.categories', 'c')
            ->select('p', 'c')
            ->andWhere('c.categoryName=:cName')
            ->setParameter('cName', $categoryName)
            ->getQuery()->getResult();
    }


    /**
     * @param $id
     * @return array
     */
    public function shopping($id): array
    {
        // returns an array of Product objects
        return $this->createQueryBuilder('p')
            ->select('p.productName', 'p.id', 'p.productPrice', 'p.productPiece', 'p.imgPath')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()->getResult();
    }


    /**
     * @param $productId
     * @return array
     */
    public function findProductByCategories($productId): array
    {

        return $this->createQueryBuilder('p')
            ->leftJoin('p.categories', 'c')
            ->where('p.id = :id')
            ->setParameter(':id', $productId)
            ->select('p', 'c')
            ->getQuery()->getResult();
    }


//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

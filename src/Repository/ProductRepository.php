<?php

namespace App\Repository;

use App\Entity\Product;
use App\Service\FilterService\FilterServiceInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
    use CommonRepositoryMethods;

    public function __construct(
        ManagerRegistry $registry,
        private readonly FilterServiceInterface $filterService
    )
    {
        parent::__construct($registry, Product::class);
    }

    public function findPaginated(): array
    {
        $qb = $this->createQueryBuilder('p');

        $this->filterService->apply($qb);

        return $qb
            ->getQuery()
            ->getResult();
    }

    public function getTotalCount(): int
    {
        return (int) $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}

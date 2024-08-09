<?php

namespace App\Repository;

use App\Entity\Advertisement;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends CrudRepository<Advertisement>
 */
class AdvertisementRepository extends CrudRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Advertisement::class);
    }

    public function findAllTracked(): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.deletedAt IS NULL')
            ->getQuery()
            ->getResult();
    }

    public function findByStatus(string $status = null, string $location = null): array
    {
        $query = $this->createQueryBuilder('a')
            ->where('a.deletedAt IS NULL');

        if ($location !== null) {
            $query->andWhere('a.location = :location')
                  ->setParameter('location', trim($location));
        }

        match ($status) {
            'inactive' => $query->andWhere('a.status <> :status')->setParameter('status', 'active'),
            'all'      => null,
            default    => $query->andWhere('a.status = :status')->setParameter('status', $status),
        };

        return $query->getQuery()->getResult();
    }

    public function findOneByUrl(string $url): ?Advertisement
    {
        return $this->createQueryBuilder('a')
            ->where('a.url = :url')
            ->setParameter('url', $url)
            ->getQuery()
            ->getOneOrNullResult();
    }
}

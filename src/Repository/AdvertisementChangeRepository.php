<?php

namespace App\Repository;

use App\Entity\AdvertisementChange;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends CrudRepository<AdvertisementChange>
 */
class AdvertisementChangeRepository extends CrudRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdvertisementChange::class);
    }

    public function findByAdvertisementIdAndProperty(int $advertisementId, string $property): array
    {
        return $this->createQueryBuilder('ac')
            ->where('ac.advertisement = :advertisement')
            ->andWhere('ac.property = :property')
            ->setParameters(new ArrayCollection([
                new Parameter('advertisement', $advertisementId),
                new Parameter('property', $property),
            ]))
            ->getQuery()
            ->getResult();
    }
}

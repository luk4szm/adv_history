<?php

namespace App\Repository;

use App\Entity\OtodomResponse;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends CrudRepository<OtodomResponse>
 */
class OtodomResponseRepository extends CrudRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OtodomResponse::class);
    }
}

<?php

declare(strict_types=1);

namespace App\Service\Otodom;

use App\Dto\OtodomAdvertisementDataDto;
use App\Entity\Advertisement;
use App\Repository\AdvertisementRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

readonly class AddOtodomAdvertisementService
{
    public function __construct(
        private AdvertisementRepository $repository,
    ) {
    }

    /**
     * Save advertisement in db
     *
     * @param OtodomAdvertisementDataDto $dto
     * @throws UniqueConstraintViolationException|\Exception
     */
    public function store(OtodomAdvertisementDataDto $dto): void
    {
        $this->repository->save(Advertisement::createFromDto($dto));
    }
}

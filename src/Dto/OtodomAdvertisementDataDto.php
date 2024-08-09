<?php

declare(strict_types=1);

namespace App\Dto;

readonly class OtodomAdvertisementDataDto
{
    public function __construct(
        public string             $status,
        public string             $url,
        public string             $title,
        public string             $location,
        public int                $price,
        public float              $area,
        public ?float             $terrainArea,
        public ?int               $buildYear,
        public array              $roomsNum,
        public ?array             $buildingType,
        public ?array             $extrasTypes,
        public ?array             $heatingTypes,
        public ?array             $mediaTypes,
        public array              $owner,
        public \DateTimeInterface $createdAt,
        public \DateTimeInterface $modifiedAt,
    ) {
    }
}

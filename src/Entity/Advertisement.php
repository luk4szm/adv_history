<?php

namespace App\Entity;

use App\Dto\OtodomAdvertisementDataDto;
use App\Repository\AdvertisementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdvertisementRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Advertisement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $url = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $location = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column]
    private ?float $area = null;

    #[ORM\Column(nullable: true)]
    private ?float $terrainArea = null;

    #[ORM\Column(nullable: true)]
    private ?int $buildYear = null;

    #[ORM\Column]
    private array $roomsNum = [];

    #[ORM\Column(nullable: true)]
    private ?array $buildingType = null;

    #[ORM\Column(nullable: true)]
    private ?array $extrasTypes = null;

    #[ORM\Column(nullable: true)]
    private ?array $heatingTypes = null;

    #[ORM\Column(nullable: true)]
    private ?array $mediaTypes = null;

    #[ORM\Column]
    private array $owner = [];

     /**
     * @var Collection<int, AdvertisementChange>
     */
    #[ORM\OneToMany(targetEntity: AdvertisementChange::class, mappedBy: 'advertisement', orphanRemoval: true)]
    private Collection $changes;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $modifiedAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    public function __construct()
    {
        $this->changes = new ArrayCollection();
    }

    public static function createFromDto(OtodomAdvertisementDataDto $dto): static
    {
        return (new self)
            ->setStatus($dto->status)
            ->setUrl($dto->url)
            ->setTitle($dto->title)
            ->setLocation($dto->location)
            ->setPrice($dto->price)
            ->setArea($dto->area)
            ->setTerrainArea($dto->terrainArea)
            ->setBuildYear($dto->buildYear)
            ->setRoomsNum($dto->roomsNum)
            ->setBuildingType($dto->buildingType)
            ->setExtrasTypes($dto->extrasTypes)
            ->setHeatingTypes($dto->heatingTypes)
            ->setMediaTypes($dto->mediaTypes)
            ->setOwner($dto->owner)
            ->setCreatedAt($dto->createdAt)
            ->setModifiedAt($dto->modifiedAt);
    }

    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getArea(): ?float
    {
        return $this->area;
    }

    public function setArea(float $area): static
    {
        $this->area = $area;

        return $this;
    }

    public function getTerrainArea(): ?float
    {
        return $this->terrainArea;
    }

    public function setTerrainArea(?float $terrainArea): static
    {
        $this->terrainArea = $terrainArea;

        return $this;
    }

    public function getBuildYear(): ?int
    {
        return $this->buildYear;
    }

    public function setBuildYear(?int $buildYear): static
    {
        $this->buildYear = $buildYear;

        return $this;
    }

    public function getRoomsNum(): array
    {
        return $this->roomsNum;
    }

    public function setRoomsNum(array $roomsNum): static
    {
        $this->roomsNum = $roomsNum;

        return $this;
    }

    public function getBuildingType(): ?array
    {
        return $this->buildingType;
    }

    public function setBuildingType(?array $buildingType): static
    {
        $this->buildingType = $buildingType;

        return $this;
    }

    public function getExtrasTypes(): ?array
    {
        return $this->extrasTypes;
    }

    public function setExtrasTypes(?array $extrasTypes): static
    {
        $this->extrasTypes = $extrasTypes;

        return $this;
    }

    public function getHeatingTypes(): ?array
    {
        return $this->heatingTypes;
    }

    public function setHeatingTypes(?array $heatingTypes): static
    {
        $this->heatingTypes = $heatingTypes;

        return $this;
    }

    public function getMediaTypes(): ?array
    {
        return $this->mediaTypes;
    }

    public function setMediaTypes(?array $mediaTypes): static
    {
        $this->mediaTypes = $mediaTypes;

        return $this;
    }

    public function getOwner(): array
    {
        return $this->owner;
    }

    public function setOwner(array $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection<int, AdvertisementChange>
     */
    public function getChanges(): Collection
    {
        return $this->changes;
    }

    public function addChange(AdvertisementChange $change): static
    {
        if (!$this->changes->contains($change)) {
            $this->changes->add($change);
            $change->setAdvertisement($this);
        }

        return $this;
    }

    public function removeChange(AdvertisementChange $change): static
    {
        if ($this->changes->removeElement($change)) {
            // set the owning side to null (unless already changed)
            if ($change->getAdvertisement() === $this) {
                $change->setAdvertisement(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getModifiedAt(): ?\DateTimeInterface
    {
        return $this->modifiedAt;
    }

    public function setModifiedAt(?\DateTimeInterface $modifiedAt): static
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeImmutable $deletedAt): static
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }
}

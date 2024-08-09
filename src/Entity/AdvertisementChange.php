<?php

namespace App\Entity;

use App\Repository\AdvertisementChangeRepository;
use App\Utils\PriceFormatter;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdvertisementChangeRepository::class)]
#[ORM\HasLifecycleCallbacks]
class AdvertisementChange
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'changes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Advertisement $advertisement = null;

    #[ORM\Column(length: 255)]
    private ?string $property = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $oldValue = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $newValue = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $changedAt = null;

    #[ORM\PrePersist]
    public function onPersist(): void
    {
        $this->changedAt = new \DateTimeImmutable();
    }

    public static function create(
        Advertisement $advertisement,
        string $property,
        string $oldValue,
        string $newValue,
    ): self
    {
        return (new self())
            ->setAdvertisement($advertisement)
            ->setProperty($property)
            ->setOldValue($oldValue)
            ->setNewValue($newValue);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdvertisement(): ?Advertisement
    {
        return $this->advertisement;
    }

    public function setAdvertisement(?Advertisement $advertisement): static
    {
        $this->advertisement = $advertisement;

        return $this;
    }

    public function getProperty(): ?string
    {
        return $this->property;
    }

    public function setProperty(string $property): static
    {
        $this->property = $property;

        return $this;
    }

    public function getOldValue(): ?string
    {
        return $this->oldValue;
    }

    public function setOldValue(?string $oldValue): static
    {
        $this->oldValue = $oldValue;

        return $this;
    }

    public function getNewValue(): ?string
    {
        return $this->newValue;
    }

    public function setNewValue(?string $newValue): static
    {
        $this->newValue = $newValue;

        return $this;
    }

    public function getChangedAt(): ?\DateTimeImmutable
    {
        return $this->changedAt;
    }

    /**
     * Return changes as readable array
     *
     * @return array
     */
    public function readAllChanges(): array
    {
        return [
            $this->getAdvertisement()->getUrl(),
            $this->getProperty(),
            $this->getOldValue(),
            $this->getNewValue(),
        ];
    }

    /**
     * Return price changes as readable array
     *
     * @return array
     */
    public function readPriceChanges(): array
    {
        return [
            $this->getChangedAt()->format('d.m.Y'),
            PriceFormatter::readable($this->getOldValue()),
            PriceFormatter::readable($this->getNewValue()),
        ];
    }
}

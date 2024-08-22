<?php

namespace App\Service\Otodom;

use App\Entity\Advertisement;
use App\Entity\AdvertisementChange;
use App\Entity\OtodomResponse;
use App\Repository\AdvertisementChangeRepository;
use App\Repository\OtodomResponseRepository;
use App\Utils\Curl\Curl;
use Doctrine\Common\Collections\ArrayCollection;

class CheckOtodomAdvertisementService
{
    public iterable $changes;

    public function __construct(
        private readonly FetchOtodomAdvertisementDataService $fetchService,
        private readonly AdvertisementChangeRepository       $changeRepository,
        private readonly OtodomResponseRepository            $responseRepository,
    ) {
        $this->changes = new ArrayCollection();
    }

    /**
     * Fetch fresh ad data from the website and compares it with those stored in the database
     *
     * @param Advertisement $advertisement
     * @throws \Exception
     */
    public function checkStatus(Advertisement $advertisement): void
    {
        $data    = Curl::get($advertisement->getUrl());
        $dto     = $this->fetchService->fetch($data);
        $changes = false;

        foreach ($dto as $property => $newValue) {
            if (in_array($property, ['owner', 'createdAt'], true)) {
                continue;
            }

            $oldValue = $advertisement->{'get' . ucfirst($property)}();

            if (
                $newValue instanceof \DateTime && $newValue->format('YmdHis') !== $oldValue->format('YmdHis')
                || !$newValue instanceof \DateTime && $newValue !== $oldValue
            ) {
                $this->saveChange($advertisement, $property, $oldValue, $newValue);

                $changes = true;
            }
        }

        if ($changes) {
            $this->saveResponse($advertisement);
        }
    }

    public function storeChanges(): void
    {
        $this->changeRepository->save($this->changes);
    }

    public function hasChanges(): bool
    {
        return !$this->changes->isEmpty();
    }

    private function saveChange(Advertisement $advertisement, string $property, mixed $oldValue, mixed $newValue): void
    {
        $this->changes->add(
            AdvertisementChange::create(
                $advertisement,
                $property,
                $this->dumpValueToString($oldValue),
                $this->dumpValueToString($newValue),
            )
        );

        $advertisement->{'set' . ucfirst($property)}($newValue);
    }

    private function saveResponse(Advertisement $advertisement): void
    {
        $response = new OtodomResponse();
        $response->setAdvertisement($advertisement)
            ->setResponse(json_encode($this->fetchService->ad));

        $this->responseRepository->save($response, false);
    }

    private function dumpValueToString(mixed $value): ?string
    {
        if (is_array($value)) {
            return json_encode($value);
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('d.m.Y H:i:s');
        }

        return $value;
    }
}

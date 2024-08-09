<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Advertisement;
use App\Entity\AdvertisementChange;
use App\Repository\AdvertisementRepository;
use App\Service\Otodom\CheckOtodomAdvertisementService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:otodom:check',
    description: 'Check all advertisement from db for changes',
)]
class OtodomCheckCommand extends Command
{
    public function __construct(
        private readonly AdvertisementRepository         $repository,
        private readonly CheckOtodomAdvertisementService $checkService,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if (empty($adverts = $this->repository->findAllTracked())) {
            $io->note('No offers found');

            return Command::SUCCESS;
        }

        $io->progressStart(count($adverts));

        /** @var Advertisement $advert */
        foreach ($adverts as $advert) {
            try {
                $this->checkService->checkStatus($advert);
            } catch (\Exception $exception) {
                $io->error("An error occurred");
                $io->note($exception->getMessage());
            }

            $io->progressAdvance();
        }

        $io->progressFinish();

        if (!$this->checkService->hasChanges()) {
            $io->info('There are no changes to any of the offers you are tracking');

            return Command::SUCCESS;
        }

        $io->info('We\'ve found some changes to the ads you\'re following');

        $io->table(
            ['url', 'property', 'old value', 'new value'],
            $this->checkService->changes->map(function (AdvertisementChange $changes) {
                return $changes->readAllChanges();
            })->toArray(),
        );

        $this->checkService->storeChanges();

        $io->success('All changes have been saved in the database');

        return Command::SUCCESS;
    }
}

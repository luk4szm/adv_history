<?php

namespace App\Command;

use App\Entity\AdvertisementChange;
use App\Repository\AdvertisementChangeRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:otodom:price',
    description: 'Lists the history of price changes for the selected ad',
)]
class OtodomPriceCommand extends Command
{
    public function __construct(
        private readonly AdvertisementChangeRepository $repository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('advertisementId', InputArgument::REQUIRED, 'ID of the advertisement you are looking for')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io      = new SymfonyStyle($input, $output);
        $changes = $this->repository->findByAdvertisementIdAndProperty($input->getArgument('advertisementId'), 'price');

        if (empty($changes)) {
            $io->info('The given ad has no price change history');

            return Command::SUCCESS;
        }

        $io->info(sprintf('Price change history for the ad [%d]', $input->getArgument('advertisementId')));
        $io->table(
            ['date', 'oldPrice', 'newPrice'],
            array_map(function (AdvertisementChange $change) {
                return $change->readPriceChanges();
            }, $changes)
        );

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}

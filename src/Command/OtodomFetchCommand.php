<?php

namespace App\Command;

use App\Service\Otodom\AddOtodomAdvertisementService;
use App\Service\Otodom\FetchOtodomAdvertisementDataService;
use App\Utils\Curl\Curl;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:otodom:add',
    description: 'Add new advertisement to db',
)]
class OtodomFetchCommand extends Command
{
    public function __construct(
        private readonly FetchOtodomAdvertisementDataService $fetchOtodomAdvertisementDataService,
        private readonly AddOtodomAdvertisementService       $addOtodomAdvertisementService,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('url', InputArgument::REQUIRED, 'URL address with the advertisement for house sale')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $data = Curl::get($input->getArgument('url'));
        } catch (\Exception $exception) {
            $io->error("An error occurred");
            $io->note($exception->getMessage());

            return Command::FAILURE;
        }

        try {
            $advertisementDto = $this->fetchOtodomAdvertisementDataService->fetch($data);
        } catch (\Exception $exception) {
            $io->error("An error occurred");
            $io->note($exception->getMessage());

            return Command::FAILURE;
        }

        try {
            $this->addOtodomAdvertisementService->store($advertisementDto);
        } catch (UniqueConstraintViolationException) {
            $io->error("The ad already exists in the database");

            return Command::FAILURE;
        } catch (\Exception $exception) {
            $io->error("An error occurred");
            $io->note($exception->getMessage());

            return Command::FAILURE;
        }

        $io->success('New ad has been added!');

        return Command::SUCCESS;
    }
}

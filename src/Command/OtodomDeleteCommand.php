<?php

namespace App\Command;

use App\Entity\Advertisement;
use App\Repository\AdvertisementRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:otodom:delete',
    description: 'Mark advertisement ad deleted',
)]
class OtodomDeleteCommand extends Command
{
    public function __construct(private readonly AdvertisementRepository $repository)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('identifier', InputArgument::REQUIRED, 'URL address or id advertisement for delete')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io         = new SymfonyStyle($input, $output);
        $identifier = $input->getArgument('identifier');

        $advertisement = is_numeric($identifier)
            ? $this->repository->find((int)$identifier)
            : $this->repository->findOneByUrl($identifier);

        if (!$advertisement instanceof Advertisement) {
            $io->error('Advertisement not found');

            return Command::SUCCESS;
        }

        $advertisement->setDeletedAt(new \DateTimeImmutable());

        $this->repository->save($advertisement);

        $io->success('Ad marked as deleted');

        return Command::SUCCESS;
    }
}

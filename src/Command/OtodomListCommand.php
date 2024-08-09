<?php

namespace App\Command;

use App\Entity\Advertisement;
use App\Repository\AdvertisementRepository;
use App\Utils\PriceFormatter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:otodom:list',
    description: 'Displays a list of tracked properties including filters',
)]
class OtodomListCommand extends Command
{
    public function __construct(
        private readonly AdvertisementRepository $repository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io     = new SymfonyStyle($input, $output);
        $helper = $this->getHelper('question');

        $question = new ChoiceQuestion(
            'Advertisement status (default: all)',
            ['all', 'active', 'inactive', 'outdated', 'removed_by_user'],
            0
        );
        $status = $helper->ask($input, $output, $question);

        $question = new Question('Property location (default: all): ');
        $location = $helper->ask($input, $output, $question);

        $adverts = $this->repository->findByStatus($status, $location);

        if (empty($adverts)) {
            $io->info('No offers found');

            return Command::SUCCESS;
        }

        $io->table(
            ['id', 'status', 'location', 'title', 'price', 'url'],
            array_map(function (Advertisement $advertisement) {
                return [
                    $advertisement->getId(),
                    $advertisement->getStatus(),
                    $advertisement->getLocation(),
                    $advertisement->getTitle(),
                    PriceFormatter::readable($advertisement->getPrice()),
                    $advertisement->getUrl(),
                ];
            }, $adverts)
        );

        return Command::SUCCESS;
    }
}

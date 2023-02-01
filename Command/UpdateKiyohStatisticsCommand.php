<?php

namespace Lens\Bundle\KiyohBundle\Command;

use Lens\Bundle\KiyohBundle\Statistics\Statistics;
use Lens\Bundle\KiyohBundle\Statistics\StatisticsRequest;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: UpdateKiyohStatisticsCommand::NAME)]
class UpdateKiyohStatisticsCommand extends Command
{
    public const NAME = 'lens:kiyoh:update';

    public function __construct(
        private readonly StatisticsRequest $statisticsRequest,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->statisticsRequest->update();

        $statistics = $this->statisticsRequest->statisticsCacheItem()->get();
        if (!($statistics instanceof Statistics)) {
            return Command::FAILURE;
        }

        $output->writeln(sprintf(
            'Currently rated %.1f with %d votes (recently rated %.1f with %d votes).',
            $statistics->rating,
            $statistics->votes,
            $statistics->recentRating,
            $statistics->recentVotes,
        ));

        $output->writeln(sprintf(
            'Timeout set for %s',
            $this->statisticsRequest->timeoutCacheItem()->get()->format('c'),
        ));

        return Command::SUCCESS;
    }
}

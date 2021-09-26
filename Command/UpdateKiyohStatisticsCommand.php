<?php

namespace Lens\Bundle\KiyohBundle\Command;

use Lens\Bundle\KiyohBundle\Statistics\Statistics;
use Lens\Bundle\KiyohBundle\Statistics\StatisticsRequest;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateKiyohStatisticsCommand extends Command
{
    protected static $defaultName = 'lens:kiyoh:update';

    private StatisticsRequest $statisticsRequest;

    public function __construct(StatisticsRequest $statisticsRequest)
    {
        parent::__construct();

        $this->statisticsRequest = $statisticsRequest;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $timeout = $this->statisticsRequest->update();

        $statistics = $this->statisticsRequest->statisticsCacheItem()->get();
        if ($statistics instanceof Statistics) {
            $output->writeln(sprintf(
                'Currently rated %.1f with %d votes (recently rated %.1f with %d votes).',
                $statistics->rating,
                $statistics->votes,
                $statistics->recentRating,
                $statistics->recentVotes
            ));
        }

        $output->writeln(sprintf(
            'Timeout set for %s',
            $timeout->format('c')
        ));

        return 0;
    }
}

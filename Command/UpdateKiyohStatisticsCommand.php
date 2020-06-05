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

    private $statisticsRequest;

    public function __construct(
        StatisticsRequest $statisticsRequest
    ) {
        parent::__construct();

        $this->statisticsRequest = $statisticsRequest;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $timeout = $this->statisticsRequest->update();

        $statistics = $this->statisticsRequest->statisticsCacheItem()->get();
        if ($statistics instanceof Statistics) {
            $output->writeln(sprintf(
                'Currently rated %s with %s votes.',
                $statistics->rating,
                $statistics->votes
            ));
        }

        $output->writeln(sprintf(
            'Timeout set for %s',
            $timeout->format('c')
        ));

        return 0;
    }
}

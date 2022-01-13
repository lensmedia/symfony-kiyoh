<?php

namespace Lens\Bundle\KiyohBundle;

use Lens\Bundle\KiyohBundle\Inviter\Inviter;
use Lens\Bundle\KiyohBundle\Inviter\RequestContent;
use Lens\Bundle\KiyohBundle\Request\Reviews;
use Lens\Bundle\KiyohBundle\Request\Statistics;
use Lens\Bundle\KiyohBundle\Statistics\StatisticsRequest;

/**
 * Simple helper all in one service class.
 * Due to kiyoh being complete bonkers, values are read from cache (or not).
 * Cache is updated behind the scenes using a cronjob (UpdateKiyohStatisticsCommand).
 */
class Kiyoh
{
    public function __construct(
        private Statistics $statistics,
        private Reviews $reviews,
        private Inviter $inviter,
    ) {
    }

    public function statistics(?string $locationName = null): ?Statistics
    {
        return $this->statistics($locationName);
    }

    public function reviews(?string $locationName = null): array
    {
        return []; // $this->statisticsRequest($locationName);
    }

    public function invite(string $email, string $name = null, string $reference = null, string $locale = null): RequestContent
    {
        return $this->inviter->invite($email, $name, $reference, $locale);
    }
}

<?php

namespace Lens\Bundle\KiyohBundle\Statistics;

use DateTime;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class StatisticsRequest
{
    const CACHE_STATISTICS_INDEX = 'lens_kiyoh.statistics';
    const CACHE_REVIEW_INDEX = 'lens_kiyoh.reviews';
    const CACHE_TTL_INDEX = 'lens_kiyoh.ttl';

    private $cache;
    private $http;
    private $options;

    public function __construct(
        CacheInterface $cache,
        HttpClientInterface $http,
        array $options
    ) {
        $this->cache = $cache;
        $this->http = $http;
        $this->options = $options;
    }

    public function timeoutCacheItem()
    {
        return $this->cache->getItem(self::CACHE_TTL_INDEX.'.'.$this->options['statistics']['hash']);
    }

    public function statisticsCacheItem()
    {
        return $this->cache->getItem(self::CACHE_STATISTICS_INDEX.'.'.$this->options['statistics']['hash']);
    }

    public function reviewsCacheItem()
    {
        return $this->cache->getItem(self::CACHE_REVIEW_INDEX.'.'.$this->options['statistics']['hash']);
    }

    public function update()
    {
        $hash = $this->options['statistics']['hash'];

        // TTL Index is used for rechecking over and over after N seconds.
        $timeout = $this->timeoutCacheItem();
        $statistics = $this->statisticsCacheItem();
        $reviews = $this->reviewsCacheItem();

        if (!$timeout->isHit() || empty($statistics->get())) {
            // This throws when the query fails, and thus does not
            // update any cache and the old cache will be returned.
            $response = $this->query();

            $reviews->set(array_map(function ($review) {
                return new Review($review);
            }, isset($response['reviews']) ? $response['reviews'] : []));
            unset($response['reviews']);
            $this->cache->save($reviews);

            if (!empty($response)) {
                $statistics->set(new Statistics($response));
                $this->cache->save($statistics);

                $expires = new DateTime('+'.$this->options['statistics']['cache_ttl'].' seconds');
                $timeout->set($expires);
                $timeout->expiresAt($expires);
                $this->cache->save($timeout);
            }
        }

        return $timeout->get();
    }

    private function query()
    {
        $target = $this->options['statistics']['base_url'].'/v1/review/feed.json';

        $response = $this->http->request('GET', $target, [
            'query' => [
                'hash' => $this->options['statistics']['hash'],
            ],
        ]);

        return $response->toArray();
    }
}

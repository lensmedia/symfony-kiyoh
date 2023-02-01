<?php

namespace Lens\Bundle\KiyohBundle\Statistics;

use DateTime;
use DateTimeImmutable;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\CacheItemInterface;

class StatisticsRequest
{
    private const CACHE_STATISTICS_INDEX = 'lens_kiyoh.statistics';
    private const CACHE_REVIEW_INDEX = 'lens_kiyoh.reviews';
    private const CACHE_TTL_INDEX = 'lens_kiyoh.ttl';

    public function __construct(
        private readonly CacheItemPoolInterface $cache,
        private readonly HttpClientInterface $http,
        private readonly array $options,
    ) {
    }

    public function timeoutCacheItem(): CacheItemInterface
    {
        return $this->cache->getItem(self::CACHE_TTL_INDEX.'.'.$this->options['statistics']['hash']);
    }

    public function statisticsCacheItem(): CacheItemInterface
    {
        return $this->cache->getItem(self::CACHE_STATISTICS_INDEX.'.'.$this->options['statistics']['hash']);
    }

    public function reviewsCacheItem(): CacheItemInterface
    {
        return $this->cache->getItem(self::CACHE_REVIEW_INDEX.'.'.$this->options['statistics']['hash']);
    }

    public function update(): void
    {
        // TTL Index is used for rechecking over and over after N seconds.
        $timeout = $this->timeoutCacheItem();
        $statistics = $this->statisticsCacheItem();
        $reviews = $this->reviewsCacheItem();

        // If timeout is still valid, and we have items don't do anything.
        if ($timeout->isHit() && !empty($statistics->get())) {
            return;
        }

        // This throws when the query fails, and thus does not
        // update any cache and the old cache will be returned.
        $response = $this->query();
        if (empty($response)) {
            return;
        }

        $reviews->set(array_map(
            static fn ($review) => new Review($review),
            $response['reviews'] ?? [],
        ));

        $this->cache->save($reviews);

        $statistics->set(new Statistics($response));
        $this->cache->save($statistics);

        $expires = new DateTimeImmutable('+'.$this->options['statistics']['cache_ttl'].' seconds');
        $timeout->set($expires);
        $timeout->expiresAt($expires);
        $this->cache->save($timeout);
    }

    private function query(): array
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

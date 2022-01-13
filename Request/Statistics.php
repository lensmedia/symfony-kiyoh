<?php

namespace Lens\Bundle\KiyohBundle\Request;

use DateTime;
use Psr\Cache\CacheItemInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Statistics
{
    const CACHE_INDEX = 'lens_kiyoh.location';

    public function __construct(
        private CacheInterface $cache,
        private HttpClientInterface $http,
        private array $options
    ) {
    }

    public function __invoke()
    {
        foreach ($this->options['locations'] as $locationName => $location) {
            $cacheItem = $this->cacheItemForLocation($locationName);
            $cacheItem->expiresAfter(1);

            dump($cacheItem, $cacheItem->isHit(), $cacheItem->get());

            $cacheItem->set(123);
        }

        dd('123');
    }

    public function cacheItemForLocation(string $locationName): CacheItemInterface
    {
        return $this->cache->getItem(self::CACHE_INDEX.'.'.$locationName);
    }

//    public function update()
//    {
//        // TTL Index is used for rechecking over and over after N seconds.
//        $timeout = $this->timeoutCacheItem();
//        $statistics = $this->statisticsCacheItem();
//        $reviews = $this->reviewsCacheItem();
//
//        if (!$timeout->isHit() || empty($statistics->get())) {
//            // This throws when the query fails, and thus does not
//            // update any cache and the old cache will be returned.
//            $response = $this->query();
//
//            $reviews->set(array_map(function ($review) {
//                return new Review($review);
//            }, isset($response['reviews']) ? $response['reviews'] : []));
//            unset($response['reviews']);
//            $this->cache->save($reviews);
//
//            if (!empty($response)) {
//                $statistics->set(new Location($response));
//                $this->cache->save($statistics);
//
//                $expires = new DateTime('+'.$this->options['statistics']['cache_ttl'].' seconds');
//                $timeout->set($expires);
//                $timeout->expiresAt($expires);
//                $this->cache->save($timeout);
//            }
//        }
//
//        return $timeout->get();
//    }
//
//    private function query(array $parameters = []): array
//    {
//        $target = $this->options['statistics']['base_url'].'/v1/review/feed.json';
//
//        $cacheIndex = http_build_query([
//                'hash' => $this->options['statistics']['hash'],
//            ] + $parameters);
//
//        dd($cacheIndex);
//
//        $response = $this->http->request('GET', $target, [
//            'query' => [
//                    'hash' => $this->options['statistics']['hash'],
//                ] + $parameters,
//        ]);
//
//        return $response->toArray();
//    }
}

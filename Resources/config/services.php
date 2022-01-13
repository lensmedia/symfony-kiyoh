<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Lens\Bundle\KiyohBundle\Inviter\Inviter;
use Lens\Bundle\KiyohBundle\Kiyoh;
use Lens\Bundle\KiyohBundle\Request\Reviews;
use Lens\Bundle\KiyohBundle\Request\Statistics;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

return static function (ContainerConfigurator $container) {
    $container->services()
//        ->set(KiyohCacheWarmer::class)
//        ->args([
//            service(KernelInterface::class),
//        ])
//        ->tag('kernel.cache_warmer')

        ->set(Inviter::class)
        ->args([
            service(HttpClientInterface::class),
            service(LoggerInterface::class),
            [],
        ])

        ->set(Statistics::class)
        ->args([
            service(CacheInterface::class),
            service(HttpClientInterface::class),
            [],
        ])

        ->set(Reviews::class)
        ->args([
            service(CacheInterface::class),
            service(HttpClientInterface::class),
            [],
        ])

        ->set(Kiyoh::class)
        ->args([
            service(Statistics::class),
            service(Reviews::class),
            service(Inviter::class),
        ])
//
//        ->set(UpdateKiyohStatisticsCommand::class)
//        ->args([
//            service(StatisticsRequest::class),
//        ])
//        ->tag('console.command')
    ;
};

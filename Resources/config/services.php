<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Lens\Bundle\KiyohBundle\KiyohCacheWarmer;
use Lens\Bundle\KiyohBundle\Command\UpdateKiyohStatisticsCommand;
use Lens\Bundle\KiyohBundle\Inviter\Inviter;
use Lens\Bundle\KiyohBundle\Kiyoh;
use Lens\Bundle\KiyohBundle\Statistics\StatisticsRequest;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set(KiyohCacheWarmer::class)
        ->args([
            service(KernelInterface::class),
        ])
        ->tag('kernel.cache_warmer')

        ->set(Inviter::class)
        ->args([
            service(HttpClientInterface::class),
            service(LoggerInterface::class),
            [],
        ])

        ->set(StatisticsRequest::class)
        ->args([
            service(CacheInterface::class),
            service(HttpClientInterface::class),
            [],
        ])

        ->set(Kiyoh::class)
        ->args([
            service(StatisticsRequest::class),
            service(Inviter::class),
        ])

        ->set(UpdateKiyohStatisticsCommand::class)
        ->args([
            service(StatisticsRequest::class),
        ])
        ->tag('console.command')
    ;
};

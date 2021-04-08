<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Lens\Bundle\KiyohBundle\Command\UpdateKiyohStatisticsCommand;
use Lens\Bundle\KiyohBundle\Inviter\Inviter;
use Lens\Bundle\KiyohBundle\Kiyoh;
use Lens\Bundle\KiyohBundle\Statistics\StatisticsRequest;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

return static function (ContainerConfigurator $container) {
    $container->services()
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

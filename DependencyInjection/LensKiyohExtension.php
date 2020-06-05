<?php

namespace Lens\Bundle\KiyohBundle\DependencyInjection;

use Lens\Bundle\KiyohBundle\Inviter\Inviter;
use Lens\Bundle\KiyohBundle\Statistics\StatisticsRequest;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class LensKiyohExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        // configuration
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // services
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );

        $loader->load('services.yaml');

        $invite = $container->getDefinition(Inviter::class);
        $invite->replaceArgument(2, $config);

        $statistics = $container->getDefinition(StatisticsRequest::class);
        $statistics->replaceArgument(2, $config);
    }
}

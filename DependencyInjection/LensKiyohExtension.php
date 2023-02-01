<?php

namespace Lens\Bundle\KiyohBundle\DependencyInjection;

use Lens\Bundle\KiyohBundle\Inviter\Inviter;
use Lens\Bundle\KiyohBundle\Statistics\StatisticsRequest;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class LensKiyohExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new PhpFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );

        $loader->load('services.php');

        $container->getDefinition(Inviter::class)
            ->replaceArgument(2, $config);

        $container->getDefinition(StatisticsRequest::class)
            ->replaceArgument(2, $config);
    }
}

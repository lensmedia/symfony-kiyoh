<?php

namespace Lens\Bundle\KiyohBundle\DependencyInjection;

use Lens\Bundle\KiyohBundle\Inviter\Inviter;
use Lens\Bundle\KiyohBundle\Request\Reviews;
use Lens\Bundle\KiyohBundle\Request\Statistics;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class LensKiyohExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new PhpFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );

        $loader->load('services.php');

        // Remap default location onto other locations.
        $locations = $config['locations'];
        if (isset($locations['_defaults'])) {
            $defaultLocation = $locations['_defaults'];
            unset($locations['_defaults']);

            $config['locations'] = array_map(function ($item) use ($defaultLocation) {
                return array_replace_recursive($defaultLocation, $item);
            }, $locations);
        }

        $container->getDefinition(Inviter::class)->replaceArgument(2, $config);
        $container->getDefinition(Statistics::class)->replaceArgument(2, $config);
        $container->getDefinition(Reviews::class)->replaceArgument(2, $config);
    }
}

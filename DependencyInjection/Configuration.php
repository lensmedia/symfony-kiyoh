<?php

namespace Lens\Bundle\KiyohBundle\DependencyInjection;

use Lens\Bundle\KiyohBundle\Inviter\Inviter;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('lens_kiyoh');

        $treeBuilder
            ->getRootNode()
            ->children()
                ->enumNode('language')
                    ->defaultValue('nl')
                    ->values(['nl', 'en'])
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('location_id')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->integerNode('tenant_id')
                    ->defaultValue(99)
                ->end()
                ->append($this->getStatisticsNode())
                ->append($this->getInvitesNode())
            ->end();

        return $treeBuilder;
    }

    public function getStatisticsNode(): NodeDefinition
    {
        $treeBuilder = new TreeBuilder('statistics');

        return $treeBuilder
            ->getRootNode()
            ->children()
                ->scalarNode('base_url')
                    ->defaultValue('https://www.kiyoh.com')
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('hash')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->integerNode('cache_ttl')
                    ->min(0)
                    ->defaultValue(60 * 60 * 12)
                ->end()
            ->end();
    }

    public function getInvitesNode(): NodeDefinition
    {
        $treeBuilder = new TreeBuilder('invites');

        return $treeBuilder
            ->getRootNode()
            ->children()
                ->scalarNode('base_url')
                    ->defaultValue('https://www.klantenvertellen.nl')
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('api_key')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->enumNode('request_type')
                    ->values([
                        Inviter::TYPE_XML,
                        Inviter::TYPE_JSON,
                        Inviter::TYPE_URL,
                    ])
                    ->defaultValue('json')
                    ->cannotBeEmpty()
                ->end()
                ->integerNode('delay')
                    ->min(0)
                    ->defaultValue(3)
                ->end()
            ->end();
    }
}

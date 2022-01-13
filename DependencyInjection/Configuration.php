<?php

namespace Lens\Bundle\KiyohBundle\DependencyInjection;

use Lens\Bundle\KiyohBundle\Inviter\Inviter;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    const TENANT_KIYOH = 98;
    const TENANT_KLANTENVERTELLEN = 99;

    const CACHE_TTL = 60 * 60 * 3;

    const BASE_URLS = [
        'https://www.kiyoh.com',
        'https://www.klantenvertellen.nl'
    ];

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('lens_kiyoh');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->enumNode('base_url')
                    ->defaultValue(self::BASE_URLS[0])
                    ->values(self::BASE_URLS)
                    ->cannotBeEmpty()
                ->end()
            ->end()
        ;

        $this->addLocationNode($rootNode);
        $this->addInviteNode($rootNode);

        return $treeBuilder;
    }

    public function addLocationNode(ArrayNodeDefinition $rootNode): NodeDefinition
    {
        return $rootNode
            ->children()
                ->arrayNode('locations')
                    ->useAttributeAsKey('name')
                    ->treatNullLike([])
                    ->arrayPrototype()
                    ->children()
                        ->scalarNode('location')->end()
                        ->integerNode('tenant')
                            ->defaultValue(self::TENANT_KIYOH)
                        ->end()
                        ->scalarNode('hash')->end()
                        ->integerNode('ttl')
                            ->min(0)
                            ->treatNullLike(self::CACHE_TTL)
                            ->defaultValue(0)
                        ->end()
                        ->arrayNode('parameters')
                            ->children()
                                ->scalarNode('locale')->end()
                                ->scalarNode('review_id')->end()
                                ->enumNode('order_by')
                                    ->values(['CREATE_DATE', 'UPDATE_DATE', 'RATING'])
                                ->end()
                                ->enumNode('sort_order')
                                    ->values(['ASC', 'DESC'])
                                ->end()
                                ->scalarNode('limit')->end()
                                ->scalarNode('date_since')->end()
                                ->scalarNode('updated_since')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();
    }

    public function addInviteNode(ArrayNodeDefinition $rootNode): NodeDefinition
    {
        return $rootNode
            ->children()
                ->arrayNode('invite')
                    ->children()
                        ->scalarNode('token')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->enumNode('request_type')
                            ->values([
                                Inviter::TYPE_XML,
                                Inviter::TYPE_JSON,
                                Inviter::TYPE_URL,
                            ])
                            ->defaultValue(Inviter::TYPE_JSON)
                            ->cannotBeEmpty()
                        ->end()
                        ->integerNode('delay')
                            ->min(0)
                            ->defaultValue(3)
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}

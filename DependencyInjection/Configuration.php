<?php

namespace Creonit\MailingBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('creonit_mailing');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('from')->isRequired()->end()
                ->scalarNode('templates_path')->end()
            ->end();

        $this->addGlobals($rootNode);

        return $treeBuilder;
    }

    public function addGlobals(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('globals')
                    ->prototype('array')
                        ->beforeNormalization()
                        ->always()
                        ->then(function ($v) {
                            if (\is_string($v) && 0 === strpos($v, '@')) {
                                return ['type' => 'service', 'value' => substr($v, 1)];
                            }

                            return ['type' => 'variable', 'value' => $v];
                        })
                        ->end()
                        ->children()
                            ->scalarNode('type')->end()
                            ->variableNode('value')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

    }
}

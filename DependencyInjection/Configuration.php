<?php

namespace Coyl\JiraApiRestClientBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $root = $treeBuilder->root('jira_client');
        $root
            ->children()
                ->arrayNode('auth')
                    ->children()
                        ->enumNode('type')->values(['basic', 'anonymus'])->defaultValue('anonymus')->end()
                        ->scalarNode('username')->end()
                        ->scalarNode('password')->end()
                        ->end()
                    ->end()
                ->scalarNode('endpoint')->isRequired()->end()
            ->end()
        ;
        return $treeBuilder;
    }

}
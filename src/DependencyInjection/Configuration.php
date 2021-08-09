<?php


namespace Kira0269\LogViewerBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    private string $defaultLogsDir;
    private string $defaultDateFormat = 'Y-m-d';

    public function __construct(string $defaultLogsDir)
    {
        $this->defaultLogsDir = $defaultLogsDir;
    }

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('kira_log_viewer');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('logs_dir')->defaultValue($this->defaultLogsDir)->end()
                ->arrayNode('file_pattern')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('date_format')->defaultValue($this->defaultDateFormat)->end()
                    ->end()
                ->end()
                ->scalarNode('log_pattern')->defaultValue('.*')->end()
                ->arrayNode('groups')
                    ->canBeUnset()
                    ->useAttributeAsKey('name')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('regex')->end()
                            ->scalarNode('type')
                                ->defaultValue('text')
                                ->validate()
                                    ->ifNotInArray(['text', 'json', 'date'])
                                    ->thenInvalid('Invalid type %s')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('dashboard')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('blocks')
                            ->arrayPrototype()
                                ->children()
                                    ->scalarNode('title')->defaultValue('Errors')->end()
                                    ->scalarNode('icon')->defaultValue('fa-calendar-check')->end()
                                    ->scalarNode('color')->defaultValue('green')->end()
                                    ->arrayNode('filter')
                                        ->arrayPrototype()
                                            ->scalarPrototype()->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}

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
                ->arrayNode('parsing_rules')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('regex')->defaultValue('.*')->end()
                        ->arrayNode('group_regexes')
                            ->canBeUnset()
                            ->useAttributeAsKey('name')
                            ->requiresAtLeastOneElement()
                            ->scalarPrototype()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
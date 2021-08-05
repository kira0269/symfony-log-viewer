<?php


namespace Kira0269\LogViewerBundle\DependencyInjection;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class LogViewerExtension extends Extension
{
    public function getAlias(){
        return 'kira_log_viewer';
    }

    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.yaml');

        $configuration = new Configuration($container->getParameter('kernel.logs_dir'));
        $config = $this->processConfiguration($configuration, $configs);

        // We prepare the final regexes by replacing group names by their specific regex
        foreach ($config['parsing_rules']['group_regexes'] as $groupName => $regex) {
            $config['parsing_rules']['regex'] = str_replace("<$groupName>", "(?<$groupName>$regex)", $config['parsing_rules']['regex']);
        }

        $definition = $container->getDefinition('Kira0269\LogViewerBundle\Services\LogParser\LogParserInterface');
        $definition->setArgument('$logsDir', $config['logs_dir']);
        $definition->setArgument('$parsingRules', $config['parsing_rules']);
    }

}
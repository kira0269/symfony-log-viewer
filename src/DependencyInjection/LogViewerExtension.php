<?php


namespace Kira0269\LogViewerBundle\DependencyInjection;


use Kira0269\LogViewerBundle\LogParser\LogParserInterface;
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
        foreach ($config['groups'] as $groupName => $groupConfig) {
            $regex = $groupConfig['regex'];
            $config['log_pattern'] = str_replace("<$groupName>", "(?<$groupName>$regex)", $config['log_pattern']);
        }

        $container->getDefinition(LogParserInterface::class)
            ->setArgument('$logsDir', $config['logs_dir'])
            ->setArgument('$filePattern', $config['file_pattern'])
            ->setArgument('$logPattern', $config['log_pattern'])
            ->setArgument('$groupsConfig', $config['groups']);

        $container->setParameter('kira_log_viewer.dashboard.blocks', $configs[0]['dashboard']['blocks']);
        $container->setParameter('kira_log_viewer.groups', $config['groups']);
    }
}

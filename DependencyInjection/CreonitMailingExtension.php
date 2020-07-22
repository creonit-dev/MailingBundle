<?php

namespace Creonit\MailingBundle\DependencyInjection;


use Creonit\MailingBundle\Config\ParameterBag;
use Creonit\MailingBundle\Message\MessageBuilderInterface;
use Creonit\MailingBundle\Templating\Loader\TemplateLoaderInterface;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

class CreonitMailingExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        $configuration = new Configuration();
        $config = $this->processConfig($container, $configuration, $configs);

        $mailingDefinition = $container->getDefinition('creonit_mailing');

        $this->addGlobals($mailingDefinition, $config['globals'] ?? []);
        unset($config['globals']);

        $configDefinition = new Definition(ParameterBag::class);
        $configDefinition->setArgument('$parameters', $config);

        $mailingDefinition->setArgument('$config', $configDefinition);

        $this->initTemplating($container, $config);
        $this->initMessageBuilders($container, $config);
    }

    protected function addGlobals(Definition $mailingDefinition, array $globals = [])
    {
        foreach ($globals as $key => $value) {
            $variableValue = $value['value'] ?? null;

            if ($value['type'] == 'service') {
                $variableValue = new Reference($variableValue);
            }

            $mailingDefinition->addMethodCall('addGlobal', [$key, $variableValue]);
        }
    }

    protected function initTemplating(ContainerBuilder $container, array $config)
    {
        $container
            ->registerForAutoconfiguration(TemplateLoaderInterface::class)
            ->addTag('creonit_mailing.template_loader');
    }

    protected function initMessageBuilders(ContainerBuilder $container, array $config)
    {
        $container
            ->registerForAutoconfiguration(MessageBuilderInterface::class)
            ->addTag('creonit_mailing.message_builder');
    }

    protected function processConfig(ContainerBuilder $container, ConfigurationInterface $configuration, array $configs)
    {
        $defaultConfig = [
            'templates_path' => "{$container->getParameter('kernel.project_dir')}/config/mailing_templates"
        ];

        $config = $this->processConfiguration($configuration, $configs);

        foreach ($config as $key => $value) {
            $container->setParameter("creonit_mailing.config.{$key}", $value);
        }

        return array_merge($defaultConfig, $config);
    }
}

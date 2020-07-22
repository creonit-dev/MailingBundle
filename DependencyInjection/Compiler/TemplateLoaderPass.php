<?php

namespace Creonit\MailingBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TemplateLoaderPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    public function process(ContainerBuilder $container)
    {
        $loaders = $this->findAndSortTaggedServices('creonit_mailing.template_loader', $container);

        $definition = $container->getDefinition('creonit_mailing.templating');

        foreach ($loaders as $loader) {
            $definition->addMethodCall('addLoader', [$loader]);
        }

        $definition->addMethodCall('load', []);
    }
}

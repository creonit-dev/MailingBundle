<?php

namespace Creonit\MailingBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MessageBuilderPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    public function process(ContainerBuilder $container)
    {
        $builders = $this->findAndSortTaggedServices('creonit_mailing.message_builder', $container);

        $definition = $container->getDefinition('creonit_mailing');
        foreach ($builders as $builder) {
            $definition->addMethodCall('addBuilder', [$builder]);
        }
    }
}

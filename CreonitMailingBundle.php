<?php

namespace Creonit\MailingBundle;

use Creonit\MailingBundle\DependencyInjection\Compiler\MessageBuilderPass;
use Creonit\MailingBundle\DependencyInjection\Compiler\TemplateLoaderPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CreonitMailingBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new TemplateLoaderPass());
        $container->addCompilerPass(new MessageBuilderPass());
    }
}

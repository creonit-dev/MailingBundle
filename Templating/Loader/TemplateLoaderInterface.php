<?php

namespace Creonit\MailingBundle\Templating\Loader;

use Creonit\MailingBundle\Templating\TemplateCollection;

interface TemplateLoaderInterface
{
    public function load(TemplateCollection $templateCollection);
}

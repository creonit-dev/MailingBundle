<?php

namespace Creonit\MailingBundle\Message;

use Creonit\MailingBundle\Templating\MailingTemplate;

interface MessageBuilderInterface
{
    public function build(MailingTemplate $template, array $context): MailingMessage;

    public function supports(MailingTemplate $template, array $context): bool;
}

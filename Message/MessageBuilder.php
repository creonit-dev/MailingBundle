<?php

namespace Creonit\MailingBundle\Message;

use Creonit\MailingBundle\Templating\MailingTemplate;
use Twig\Environment;

class MessageBuilder implements MessageBuilderInterface
{
    /**
     * @var Environment
     */
    protected Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function build(MailingTemplate $template, array $context): MailingMessage
    {
        $message = new MailingMessage();
        $message
            ->subject($template->getSubject())
            ->html($this->renderTemplate($template, $context));

        if (is_string($template->getFrom())) {
            $message->from($template->getFrom());
        }

        if (is_string($template->getCopy())) {
            $message->cc($template->getCopy());
        }

        return $message;
    }

    protected function renderTemplate(MailingTemplate $template, array $context)
    {
        if ($this->twig->getLoader()->exists($template->getTemplate())) {
            return $this->twig->render($template->getTemplate(), $context);
        }

        return $this->twig->createTemplate($template->getTemplate())->render($context);
    }

    public function supports(MailingTemplate $template, array $context): bool
    {
        return true;
    }
}

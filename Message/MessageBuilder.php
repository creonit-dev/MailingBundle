<?php

namespace Creonit\MailingBundle\Message;

use Creonit\MailingBundle\Templating\MailingTemplate;
use Twig\Environment;
use Twig\TemplateWrapper;

class MessageBuilder implements MessageBuilderInterface
{
    /**
     * @var Environment
     */
    protected $twig;

    protected $baseTemplate;

    public function __construct(Environment $twig, string $baseTemplate)
    {
        $this->twig = $twig;
        $this->baseTemplate = $baseTemplate;
    }

    public function build(MailingTemplate $template, array $context): MailingMessage
    {
        $message = new MailingMessage();
        $message
            ->subject($template->getSubject())
            ->html($this->renderTemplate($template, $context));

        if ($template->getFrom()) {
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

        return $this->createTemplate($template)->render($context);
    }

    protected function createTemplate(MailingTemplate $template): TemplateWrapper
    {
        $content = $template->getTemplate();

        if ($this->baseTemplate) {
            $content = "{% extends '{$this->baseTemplate}' %} {% block content %}{$content}{% endblock %}";
        }

        return $this->twig->createTemplate($content);
    }

    public function supports(MailingTemplate $template, array $context): bool
    {
        return true;
    }
}

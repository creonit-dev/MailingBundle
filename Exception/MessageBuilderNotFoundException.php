<?php

namespace Creonit\MailingBundle\Exception;

use Creonit\MailingBundle\Templating\MailingTemplate;
use Throwable;

class MessageBuilderNotFoundException extends \Exception
{
    /**
     * @var MailingTemplate
     */
    protected $template;

    protected $context;

    public function __construct(MailingTemplate $template, array $context, $message = "", $code = 0, Throwable $previous = null)
    {
        $this->template = $template;
        $this->context = $context;

        if (!$message) {
            $message = "Not message builder for template '{$template->getKey()}' ({$template->getResource()})";
        }

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return MailingTemplate
     */
    public function getTemplate(): MailingTemplate
    {
        return $this->template;
    }

    /**
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }
}

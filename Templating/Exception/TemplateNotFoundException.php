<?php

namespace Creonit\MailingBundle\Templating\Exception;

use Throwable;

class TemplateNotFoundException extends \Exception
{
    protected string $templateKey;

    public function __construct(string $templateKey, $message = "", $code = 0, Throwable $previous = null)
    {
        if (!$message) {
            $message = "Template '{$templateKey}' not found";
        }

        $this->templateKey = $templateKey;

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function getTemplateKey(): string
    {
        return $this->templateKey;
    }
}

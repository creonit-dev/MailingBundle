<?php

namespace Creonit\MailingBundle;


use Creonit\MailingBundle\Config\ParameterBag;
use Creonit\MailingBundle\Exception\NotFoundMessageBuilderException;
use Creonit\MailingBundle\Message\MailingMessage;
use Creonit\MailingBundle\Message\MessageBuilderInterface;
use Creonit\MailingBundle\Templating\Templating;

class Mailing
{
    /**
     * @var array
     */
    protected $globals = [];

    /**
     * @var ParameterBag
     */
    protected $config;

    /**
     * @var Templating
     */
    protected $templating;

    /**
     * @var MessageBuilderInterface[]
     */
    protected $builders = [];

    public function __construct(ParameterBag $config, Templating $templating)
    {
        $this->config = $config;
        $this->templating = $templating;
    }

    public function addGlobal(string $key, $value)
    {
        $this->globals[$key] = $value;
    }

    public function getGlobal(string $key)
    {
        return $this->globals[$key] ?? null;
    }

    public function addBuilder(MessageBuilderInterface $builder)
    {
        $this->builders[] = $builder;
    }

    public function buildMessage(string $templateKey, array $context = []): MailingMessage
    {
        $template = $this->templating->getTemplate($templateKey);
        $this->injectGlobals($context);

        foreach ($this->builders as $builder) {
            if ($builder->supports($template, $context)) {
                $message = $builder->build($template, $context);

                if (empty($message->getFrom())) {
                    $message->from($this->config->get('from'));
                }

                return $message;
            }
        }

        throw new NotFoundMessageBuilderException($template, $context);
    }

    protected function injectGlobals(array &$context)
    {
        $context = array_merge($this->globals, $context);
    }
}

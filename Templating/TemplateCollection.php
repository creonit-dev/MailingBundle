<?php

namespace Creonit\MailingBundle\Templating;


class TemplateCollection
{
    /**
     * @var MailingTemplate[]
     */
    protected $templates = [];

    public function all()
    {
        return $this->templates;
    }

    public function has(string $key)
    {
        return array_key_exists($key, $this->templates);
    }

    public function get(string $key)
    {
        return $this->templates[$key] ?? null;
    }

    public function add(MailingTemplate $template)
    {
        $this->templates[$template->getKey()] = $template;

        return $this;
    }
}

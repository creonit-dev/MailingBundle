<?php

namespace Creonit\MailingBundle\Templating;

class TemplateCollection implements \Countable, \IteratorAggregate
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

    public function keys()
    {
        return array_keys($this->templates);
    }

    public function add(MailingTemplate $template)
    {
        $this->templates[$template->getKey()] = $template;

        return $this;
    }

    public function count()
    {
        return count($this->templates);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->templates);
    }
}

<?php

namespace Creonit\MailingBundle\Templating;


use Symfony\Component\Mime\Address;

class MailingTemplate
{
    protected $key;
    protected $title;
    protected $subject;

    /**
     * @var string|Address
     */
    protected $from = '';

    protected $copy;
    protected $template;
    protected $resource = '';

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string|Address
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param string|Address $from
     *
     * @return $this
     */
    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param mixed $template
     *
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @return string
     */
    public function getResource(): string
    {
        return $this->resource;
    }

    /**
     * @param string $resource
     *
     * @return $this
     */
    public function setResource(string $resource)
    {
        $this->resource = $resource;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     *
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCopy()
    {
        return $this->copy;
    }

    /**
     * @param mixed $copy
     *
     * @return $this
     */
    public function setCopy($copy)
    {
        $this->copy = $copy;
        return $this;
    }
}

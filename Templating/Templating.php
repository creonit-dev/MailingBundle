<?php

namespace Creonit\MailingBundle\Templating;

use Creonit\MailingBundle\Templating\Exception\TemplateNotFoundException;
use Creonit\MailingBundle\Templating\Loader\TemplateLoaderInterface;

class Templating
{
    /**
     * @var TemplateLoaderInterface[]
     */
    protected $loaders = [];

    /**
     * @var TemplateCollection
     */
    protected $templates;

    public function __construct()
    {
        $this->templates = new TemplateCollection();
    }

    public function addLoader(TemplateLoaderInterface $templateLoader)
    {
        $this->loaders[] = $templateLoader;
    }

    public function load()
    {
        $this->templates = new TemplateCollection();

        foreach ($this->loaders as $loader) {
            $loader->load($this->templates);
        }

        return $this->templates;
    }

    public function getTemplate(string $key)
    {
        if (!$this->templates->has($key)) {
            throw new TemplateNotFoundException($key);
        }

        return $this->templates->get($key);
    }

    /**
     * @return TemplateCollection|MailingTemplate[]
     */
    public function getTemplates(): TemplateCollection
    {
        return $this->templates;
    }
}

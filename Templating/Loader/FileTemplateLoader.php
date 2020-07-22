<?php

namespace Creonit\MailingBundle\Templating\Loader;

use Creonit\MailingBundle\Templating\TemplateCollection;
use Symfony\Component\Finder\Finder;

abstract class FileTemplateLoader extends AbstractTemplateLoader
{
    protected $namePattern = '';
    protected $templatesPath;

    public function __construct(string $templatesPath)
    {
        $this->templatesPath = $templatesPath;
    }

    public function load(TemplateCollection $templateCollection)
    {
        $finder = new Finder();

        $finder->in($this->templatesPath);

        if ($this->namePattern) {
            $finder->name($this->namePattern);
        }

        foreach ($finder as $file) {
            $this->loadFile($file->getPathname(), $templateCollection);
        }
    }

    abstract protected function loadFile(string $path, TemplateCollection $templateCollection);
}

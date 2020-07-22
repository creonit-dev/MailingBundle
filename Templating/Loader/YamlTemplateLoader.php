<?php

namespace Creonit\MailingBundle\Templating\Loader;


use Creonit\MailingBundle\Templating\Exception\InvalidConfigurationException;
use Creonit\MailingBundle\Templating\MailingTemplate;
use Creonit\MailingBundle\Templating\TemplateCollection;
use Symfony\Component\Yaml\Yaml;

class YamlTemplateLoader extends FileTemplateLoader
{
    protected function loadFile(string $path, TemplateCollection $templateCollection)
    {
        if (!$content = Yaml::parseFile($path)) {
            return;
        }

        foreach ($content as $key => $config) {
            $this->validate($path, $key, $config);

            $template = new MailingTemplate($key);

            $template
                ->setResource($path)
                ->setTitle($config['title'])
                ->setSubject($config['subject'])
                ->setTemplate($config['template'])
                ->setFrom($config['from'] ?? null);

            $templateCollection->add($template);
        }
    }

    protected function validate(string $resource, string $key, array $config)
    {
        $requiredFields = ['title', 'template', 'subject'];

        if (!empty($undefinedFields = array_diff($requiredFields, array_keys($config)))) {
            throw new InvalidConfigurationException(sprintf('Not defined parameters [%s] for template "%s" in %s', implode(', ', $undefinedFields), $key, $resource));
        }

        $stringFields = ['title', 'template', 'subject'];

        foreach ($stringFields as $field) {
            if (!is_string($config[$field])) {
                throw new InvalidConfigurationException(sprintf('Parameter "%s" is not string. Template "%s" in %s', $field, $key, $resource));
            }
        }
    }
}

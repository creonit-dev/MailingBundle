<?php

namespace Creonit\MailingBundle\Templating\Loader;


use Creonit\MailingBundle\Model\MailingTemplate;
use Creonit\MailingBundle\Model\MailingTemplateQuery;
use Creonit\MailingBundle\Templating\TemplateCollection;
use Symfony\Component\Mime\Address;

class DatabaseTemplateLoader extends AbstractTemplateLoader
{
    public function load(TemplateCollection $templateCollection)
    {
        $templates = $this->getTemplates($templateCollection->keys());

        foreach ($templates as $template) {
            $mailingTemplate = $templateCollection->get($template->getKey());
            $mailingTemplate
                ->setTitle($template->getTitle())
                ->setSubject($template->getSubject())
                ->setTemplate($template->getTemplate())
                ->setFrom($this->normalizeFrom($template->getFrom()));
        }
    }

    /**
     * @param array $keys
     *
     * @return \Propel\Runtime\Collection\ObjectCollection|MailingTemplate[]
     */
    protected function getTemplates(array $keys)
    {
        return MailingTemplateQuery::create()
            ->findPks($keys);
    }

    protected function normalizeFrom($from)
    {
        if (!$from) {
            return null;
        }

        if (is_array($from)) {
            return new Address($from['email'] ?? '', $from['name'] ?? '');
        }

        return $from;
    }
}

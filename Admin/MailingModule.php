<?php

namespace Creonit\MailingBundle\Admin;

use Creonit\AdminBundle\Module;
use Creonit\MailingBundle\Admin\Template\TemplateEditor;
use Creonit\MailingBundle\Admin\Template\TemplateTable;

class MailingModule extends Module
{
    protected function configure()
    {
        $this
            ->setIcon('envelope')
            ->setTitle('Настройка почты')
            ->setTemplate('TemplateTable');
    }

    public function initialize()
    {
        $this->addComponentAsService(TemplateTable::class);
        $this->addComponentAsService(TemplateEditor::class);
    }
}

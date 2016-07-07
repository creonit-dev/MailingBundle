<?php

namespace Creonit\MailingBundle\Admin;

use Creonit\AdminBundle\Component\TableComponent;

class TemplateTable extends TableComponent
{

    /**
     * @title Список шаблонов
     * @header
     * {{ button('Добавить шаблон', {size: 'sm', type: 'success', icon: 'clipboard'}) | open('Mailing.TemplateEditor') }}
     *
     * @cols Идентификатор, Заголовок, .
     *
     * \MailingTemplate
     * @entity Creonit\MailingBundle\Model\MailingTemplate
     * @sortable true
     *
     * @col {{ name | open('Mailing.TemplateEditor', {key: _key}) | controls }}
     * @col {{ title }}
     * @col {{ _delete() }}
     *
     *
     */
    public function schema()
    {
    }
}
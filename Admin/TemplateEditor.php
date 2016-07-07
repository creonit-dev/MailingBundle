<?php

namespace Creonit\MailingBundle\Admin;

use Creonit\AdminBundle\Component\EditorComponent;

class TemplateEditor extends EditorComponent
{

    /**
     * @title Шаблон
     * @entity Creonit\MailingBundle\Model\MailingTemplate
     *
     * @field title {required: true}
     * @field name {required: true}
     * @field content {required: true}
     *
     * @template
     * {{ name | text | group('Идентификатор') }}
     * {{ title | text | group('Заголовок') }}
     * {{ content | textarea | group('Содержание') }}
     * {{ variables | textarea | group('Примечание', {notice: 'Для служебного использования'}) }}
     */
    public function schema()
    {
    }
}
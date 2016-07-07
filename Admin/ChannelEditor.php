<?php

namespace Creonit\MailingBundle\Admin;

use Creonit\AdminBundle\Component\EditorComponent;

class ChannelEditor extends EditorComponent
{


    /**
     * @title Канал
     * @entity Creonit\MailingBundle\Model\MailingChannel
     *
     * @field title {required: true}
     * @field name {required: true}
     *
     * @template
     * {{ title | text | group('Название') }}
     * {{ name | text | group('Идентификатор') }}
     */
    public function schema()
    {
    }
}
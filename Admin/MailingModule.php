<?php

namespace Creonit\MailingBundle\Admin;

use Creonit\AdminBundle\Module;

class MailingModule extends Module
{

    public function initialize()
    {
        $this->addComponent(new ChannelTable);
        $this->addComponent(new ChannelEditor);
        $this->addComponent(new ChooseChannelTable);

        $this->addComponent(new SubscriberTable);
        $this->addComponent(new SubscriberEditor);
        
        $this->addComponent(new TemplateTable);
        $this->addComponent(new TemplateEditor);
                
        $this->addComponent(new BroadcastTable);
        $this->addComponent(new BroadcastEditor);
        
    }

    public function getTemplate()
    {
        return '<div js-component="Mailing.SubscriberTable">';
    }

    public function getIcon()
    {
        return 'fa fa-envelope-o';
    }

    public function getTitle(){
        return 'Рассылка';
    }
}
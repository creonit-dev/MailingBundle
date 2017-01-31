<?php

namespace Creonit\MailingBundle\Admin;

use Creonit\AdminBundle\Module;

class MailingModule extends Module
{

    protected function configure()
    {
        $this
            ->setTitle('Рассылка')
            ->setIcon('envelope-o')
            ->setTemplate('SubscriberTable')
        ;
    }

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

}
<?php

namespace Creonit\MailingBundle\Model;

use Creonit\MailingBundle\Model\Base\MailingSubscriber as BaseMailingSubscriber;

class MailingSubscriber extends BaseMailingSubscriber
{

    public function getSubscriberEmail()
    {
        return $this->user_id ? $this->getUser()->getEmail() ?: $this->email : $this->email;
    }

    public function getSubscriberTitle()
    {
        return $this->user_id ? $this->getUser()->getTitle() ?: $this->title : $this->title;
    }

}

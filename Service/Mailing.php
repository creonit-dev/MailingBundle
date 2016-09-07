<?php

namespace Creonit\MailingBundle\Service;

use Creonit\MailingBundle\Model\MailingBroadcast;
use Creonit\MailingBundle\Model\MailingBroadcastQuery;
use Creonit\MailingBundle\Model\MailingChannelQuery;
use Creonit\MailingBundle\Model\MailingSubscriberQuery;
use Creonit\MailingBundle\Model\MailingTemplate;
use Creonit\MailingBundle\Model\MailingTemplateQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Symfony\Bundle\TwigBundle\TwigEngine;

class Mailing
{

    /** @var  \Swift_Mailer */
    protected $mailer;

    /** @var  \Twig_Environment */
    protected $templating;

    /** @var MailingTemplate[] */
    protected $templates = [];

    protected $template;

    protected $from;

    public function setMailer(\Swift_Mailer $mailer){
        $this->mailer = $mailer;
    }

    public function setTemplating(\Twig_Environment $templating)
    {
        $this->templating = $templating;
    }

    public function setTemplate($template)
    {
        $this->template = $template;
    }

    public function setFrom($from){
        $this->from = $from;
    }

    public function getSubscribers($channelName){
        if($channel = MailingChannelQuery::create()->findOneByName($channelName)){

            $subscribers = [];

            foreach(
                MailingSubscriberQuery::create()
                    ->useMailingSubscriberChannelQuery()
                        ->filterByMailingChannel($channel)
                        ->endUse()
                    ->find()
                as $subscriber
            ){
                $subscribers[$subscriber->getSubscriberEmail()] = $subscriber->getSubscriberTitle();
            }

            return $subscribers;

        }

        return [];
    }

    /**
     * @param string $date
     * @return \Creonit\MailingBundle\Model\MailingBroadcast[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function getBroadcasts($date = 'now'){
        $date = new \DateTime($date);

        return MailingBroadcastQuery::create()
            ->filterByStatus(MailingBroadcast::STATUS_READY)
            ->filterByStartedAt($date, Criteria::LESS_EQUAL)
            ->find();

    }

    public function getBroadcastSubscribers(MailingBroadcast $broadcast){
        $subscribers = [];
        foreach($broadcast->getMailingBroadcastChannelsJoinMailingChannel() as $broadcastChannel){
            $subscribers = array_replace($subscribers, $this->getSubscribers($broadcastChannel->getMailingChannel()->getName()));
        }
        return $subscribers;
    }

    public function createMessage($subject, $body)
    {
        $message = $this->mailer->createMessage()
            ->setSubject($subject)
            ->setFrom($this->from);

        if(is_array($body)){
            $name = key($body);
            if(is_integer($name)){
                $name = $body;
                $parameters = [];
            }else{
                $parameters = $body[$name];
            }

            if(!$subject){
                $message->setSubject($this->getTemplate($name)->getTitle());
            }

            $body = $this->renderTemplate($name, $parameters);
        }


        $message->setBody($body, 'text/html', 'utf-8');
        $message->setEncoder(\Swift_Encoding::get7BitEncoding());

        return $message;

    }

    public function send(\Swift_Message $message, $to = null){
        if(null !== $to){
            $message->setTo($to);
        }

        return $this->mailer->send($message);
    }

    public function getTemplate($name){
        if(isset($this->templates[$name])){
            return $this->templates[$name];
        }

        if($template = MailingTemplateQuery::create()->findOneByName($name)){
            return $this->templates[$name] = $template;
        }else{
            throw new \Exception(sprintf('Mailing template %s not found', $template));
        }
    }

    public function renderTemplate($name, $parameters){
        return $this->templating->createTemplate(
                $this->template
                    ? "{% extends '{$this->template}' %} {% block body %}" . $this->getTemplate($name)->getContent() . '{% endblock %}'
                    : $this->getTemplate($name)->getContent()
            )
            ->render($parameters);
    }

}
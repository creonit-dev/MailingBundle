<?php

namespace Creonit\MailingBundle\Admin;

use Creonit\AdminBundle\Component\Request\ComponentRequest;
use Creonit\AdminBundle\Component\Response\ComponentResponse;
use Creonit\AdminBundle\Component\Scope\Scope;
use Creonit\AdminBundle\Component\TableComponent;
use Creonit\MailingBundle\Model\MailingSubscriber;
use Symfony\Component\HttpFoundation\ParameterBag;

class SubscriberTable extends TableComponent
{

    /**
     * @title Подписчики
     * @header
     * {{ button('Добавить подписчика', {size: 'sm', type: 'success', icon: 'user'}) | open('Mailing.SubscriberEditor') }}
     * {{ button('Каналы', {size: 'sm', icon: 'bars'}) | open('Mailing.ChannelTable') }}
     * {{ button('Рассылка', {size: 'sm', icon: 'envelope-o'}) | open('Mailing.BroadcastTable') }}
     * {{ button('Шаблоны', {size: 'sm', icon: 'clipboard'}) | open('Mailing.TemplateTable') }}
     *
     * @cols Подписчики, Имя, '', .
     *
     * \MailingSubscriber
     * @entity Creonit\MailingBundle\Model\MailingSubscriber
     * @pagination 20
     * @field email {load: 'entity.getSubscriberEmail()'}
     * @field title {load: 'entity.getSubscriberTitle()'}
     *
     * @col {{ email | icon('share') | open('Mailing.SubscriberEditor', {key: _key}) }}
     * @col {{ title }}
     * @col {{ button(channels, {icon: 'bars', size: 'xs'}) | open('Mailing.ChooseChannelTable', {subscriber: _key}) }}
     * @col {{ _delete() }}
     */
    public function schema()
    {
    }

    /**
     * @param ComponentRequest $request
     * @param ComponentResponse $response
     * @param ParameterBag $data
     * @param MailingSubscriber $entity
     * @param Scope $scope
     * @param $relation
     * @param $relationValue
     * @param $level
     */
    protected function decorate(ComponentRequest $request, ComponentResponse $response, ParameterBag $data, $entity, Scope $scope, $relation, $relationValue, $level)
    {
        $channels = $entity->countMailingSubscriberChannels();
        $data->set('channels', !$channels ? 'Нет каналов' : $channels . ' ' . $this->container->get('translator')->transChoice('channel', $channels, [], 'admin'));
    }


}
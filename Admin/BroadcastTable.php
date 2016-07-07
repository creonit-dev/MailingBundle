<?php

namespace Creonit\MailingBundle\Admin;

use Creonit\AdminBundle\Component\Request\ComponentRequest;
use Creonit\AdminBundle\Component\Response\ComponentResponse;
use Creonit\AdminBundle\Component\Scope\Scope;
use Creonit\AdminBundle\Component\TableComponent;
use Creonit\MailingBundle\Model\MailingBroadcast;
use Creonit\MailingBundle\Model\MailingBroadcastQuery;
use Symfony\Component\HttpFoundation\ParameterBag;

class BroadcastTable extends TableComponent
{

    /**
     * @title Список рассылок
     * @cols Заголовок, Каналы, Статус, ., Дата отправки, .
     * @header
     * {{ button('Добавить рассылку', {size: 'sm', type: 'success', icon: 'envelope-o'}) | open('Mailing.BroadcastEditor') }}
     *
     * @action action(action, broadcast){
     *      var component = this;
     *      component.request('action', {action: action, broadcast: broadcast}, {}, function(response){
     *           component.checkResponse(response);
     *      });
     *      component.loadData();
     * }

     *
     *
     * \MailingBroadcast
     * @entity Creonit\MailingBundle\Model\MailingBroadcast
     * @field status
     *
     * @col {{ title | icon('envelope-o') | open('Mailing.BroadcastEditor', {key: _key}) }}
     * @col
     *      {% if not status %}
     *          {{ button(channels | raw, {icon: 'bars', size: 'xs'}) | open('Mailing.ChooseChannelTable', {broadcast: _key}) }}
     *      {% else %}
     *          {{ channels | raw }}
     *      {% endif %}
     * @col
     * {% if channels_count %}
     *      {% if status == 1 %}
     *          Ожидает отправки
     *      {% elseif status == 2 %}
     *          Выполнено
     *      {% elseif status == 3 %}
     *          Ошибка
     *      {% else %}
     *          Подготовка
     *      {% endif %}
     * {% else %}
     *      {{ 'Выберите каналы' | icon('exclamation')}}
     * {% endif %}
     *
     * @col
     * {% if channels_count %}
     *      {% if status == 1 %}
     *          {{ button('Остановить', {icon: 'stop', size: 'xs'}) | action('action', 'stop', _key) }}
     *      {% elseif status == 0 %}
     *          {{ button('Запустить', {icon: 'play', size: 'xs'}) | action('action', 'start', _key) }}
     *      {% endif %}
     * {% endif %}
     *
     * @col {{ started_at | date('d.m.Y H:i') }}
     * @col {{ _delete() }}
     *
     */
    public function schema(){

        $this->setHandler('action', function(ComponentRequest $request, ComponentResponse $response){
            $action = $request->query->get('action');
            $broadcast = $request->query->get('broadcast');

            if(!in_array($action, ['start', 'stop'])){
                $response->flushError('Событие не найдено');
            }

            if(!$broadcast or !$broadcast = MailingBroadcastQuery::create()->findPk($broadcast)){
                $response->flushError('Рассылка не найдена');
            }

            if(
                ($action == 'start' && $broadcast->getStatus() == MailingBroadcast::STATUS_CREATED) or
                ($action == 'stop' && $broadcast->getStatus() == MailingBroadcast::STATUS_READY)
            ){
                $broadcast->setStatus($action == 'start' ? MailingBroadcast::STATUS_READY : MailingBroadcast::STATUS_CREATED)->save();

            }


        });
    }

    /**
     * @param ComponentRequest $request
     * @param ComponentResponse $response
     * @param ParameterBag $data
     * @param MailingBroadcast $entity
     * @param Scope $scope
     * @param $relation
     * @param $relationValue
     * @param $level
     */
    protected function decorate(ComponentRequest $request, ComponentResponse $response, ParameterBag $data, $entity, Scope $scope, $relation, $relationValue, $level)
    {
        $channels = $entity->countMailingBroadcastChannels();
        $data->set('channels_count', $channels);
        $data->set('channels', !$channels ? 'Нет каналов' : $channels . '&nbsp;' . $this->container->get('translator')->transChoice('channel', $channels, [], 'admin'));
    }


}
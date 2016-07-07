<?php

namespace Creonit\MailingBundle\Admin;


use Creonit\AdminBundle\Component\Request\ComponentRequest;
use Creonit\AdminBundle\Component\Response\ComponentResponse;
use Creonit\AdminBundle\Component\Scope\Scope;
use Creonit\AdminBundle\Component\TableComponent;
use Creonit\MailingBundle\Model\MailingBroadcastChannel;
use Creonit\MailingBundle\Model\MailingBroadcastChannelQuery;
use Creonit\MailingBundle\Model\MailingBroadcastQuery;
use Creonit\MailingBundle\Model\MailingChannelQuery;
use Creonit\MailingBundle\Model\MailingSubscriberChannel;
use Creonit\MailingBundle\Model\MailingSubscriberChannelQuery;
use Creonit\MailingBundle\Model\MailingSubscriberQuery;
use Symfony\Component\HttpFoundation\ParameterBag;

class ChooseChannelTable extends TableComponent
{

    protected $activeChannels = [];

    /**
     * @title Выберите каналы
     * @action choose(options){
     *      var $row = this.findRowById(options.rowId);
     *      $row.toggleClass('success')
     *
     *      this.request('choose', $.extend({key: options.key}, this.getQuery()), {state: $row.hasClass('success')});
     *      this.parent.loadData();
     * }
     *
     * @cols Название, Идентификатор
     *
     *
     * \MailingChannel
     * @entity Creonit\MailingBundle\Model\MailingChannel
     *
     * @col {{ title | action('choose', {key: _key, rowId: _row_id}) }}
     * @col {{ name }}
     */
    public function schema()
    {
        $this->setHandler('choose', function (ComponentRequest $request, ComponentResponse $response) {
            $channel = MailingChannelQuery::create()->findPk($request->query->get('key')) or $response->flushError('Канал не найден');


            if($request->query->has('subscriber')){
                $subscriber = MailingSubscriberQuery::create()->findPk($request->query->get('subscriber')) or $response->flushError('Подписчик не найден');
          
                $rel = MailingSubscriberChannelQuery::create()->filterByMailingChannel($channel)->filterByMailingSubscriber($subscriber)->findOne();
                if($request->data->getBoolean('state')){
                    if(!$rel){
                        $rel = new MailingSubscriberChannel();
                        $rel->setMailingChannel($channel);
                        $rel->setMailingSubscriber($subscriber);
                        $rel->save();
                    }
                }else{
                    if($rel){
                        $rel->delete();
                    }
                }


            }else if($request->query->has('broadcast')){
                $broadcast = MailingBroadcastQuery::create()->findPk($request->query->get('broadcast')) or $response->flushError('Рассылка не найдена');

                $rel = MailingBroadcastChannelQuery::create()->filterByMailingChannel($channel)->filterByMailingBroadcast($broadcast)->findOne();
                if($request->data->getBoolean('state')){
                    if(!$rel){
                        $rel = new MailingBroadcastChannel;
                        $rel->setMailingChannel($channel);
                        $rel->setMailingBroadcast($broadcast);
                        $rel->save();
                    }
                }else{
                    if($rel){
                        $rel->delete();
                    }
                }


            }

        });
    }

    protected function decorate(ComponentRequest $request, ComponentResponse $response, ParameterBag $data, $entity, Scope $scope, $relation, $relationValue, $level)
    {
        if(in_array($entity->getId(), $this->activeChannels)){
            $data->set('_row_class', 'success');
        }
    }

    protected function loadData(ComponentRequest $request, ComponentResponse $response)
    {

        if($request->query->has('subscriber')){
            $this->activeChannels = MailingSubscriberChannelQuery::create()
                ->filterBySubscriberId($request->query->get('subscriber'))
                ->select(['ChannelId'])->find()->getData();

        }else if($request->query->has('broadcast')){
            $this->activeChannels = MailingBroadcastChannelQuery::create()
                ->filterByBroadcastId($request->query->get('broadcast'))
                ->select(['ChannelId'])->find()->getData();
        }

        parent::loadData($request, $response);
    }
}
<?php

namespace Creonit\MailingBundle\Admin;

use Creonit\AdminBundle\Component\EditorComponent;
use Creonit\AdminBundle\Component\Request\ComponentRequest;
use Creonit\AdminBundle\Component\Response\ComponentResponse;
use Creonit\MailingBundle\Model\MailingBroadcast;

class BroadcastEditor extends EditorComponent
{

   /**
    * @title Рассылка
    * @entity Creonit\MailingBundle\Model\MailingBroadcast
    * @field status
    * @template
    *
    * {% if not status %}
    *    {{ title | text | group('Заголовок') }}
    *    {{ content | textedit | group('Сообщение') }}
    *    {{ started_at | input('datetime') | group('Дата отправки сообщения', {notice: 'Оставьте пустым для назначения текущей даты'}) }}
    * {% else %}
    *    <div class="alert alert-warning" role="alert">{{ status == 1 ? 'Остановите рассылку, чтобы вносить изменения!' : 'Внесение изменений невозможно' }}</div>
    *    {{ title | panel | group('Заголовок') }}
    *    {{ content | raw | panel | group('Сообщение') }}
    *    {{ started_at | panel | group('Дата отправки сообщения', {notice: 'Оставьте пустым для назначения текущей даты'}) }}
    * {% endif %}
    *
    */
   public function schema(){
   }

   public function validate(ComponentRequest $request, ComponentResponse $response, $entity)
   {
      if($entity->getStatus() != MailingBroadcast::STATUS_CREATED){
         $response->flushError('Остановите рассылку, чтобы вносить изменения');
      }
   }

   public function preSave(ComponentRequest $request, ComponentResponse $response, $entity)
   {
      if(!$request->data->get('started_at')){
         $entity->setStartedAt('now');
      }
   }


}
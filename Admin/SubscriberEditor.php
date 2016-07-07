<?php

namespace Creonit\MailingBundle\Admin;

use Creonit\AdminBundle\Component\EditorComponent;
use Creonit\AdminBundle\Component\Request\ComponentRequest;
use Creonit\AdminBundle\Component\Response\ComponentResponse;
use Creonit\MailingBundle\Model\MailingSubscriber;
use Creonit\MailingBundle\Model\MailingSubscriberQuery;

class SubscriberEditor extends EditorComponent
{

    /**
     * @title Подписчик
     * @entity Creonit\MailingBundle\Model\MailingSubscriber
     *
     * @field email {constraints: Email()}
     * @field user_id:external {title: 'entity.getUser().getTitle()'}
     *
     * @template
     * {{ user_id | external('User.ChooseUserTable', {empty: 'Без привязки к пользователю'}) | group('Пользователь') }}
     *
     * {{ title | text | group('Имя', {notice: 'Не заполняйте, если выбран пользователь'}) }}
     * {{ email | text | group('Электронная почта', {notice: 'Не заполняйте, если выбран пользователь'}) }}
     */
    public function schema()
    {
    }

    public function validate(ComponentRequest $request, ComponentResponse $response, $entity)
    {
        if(!$request->data->get('user_id') && !$request->data->get('email')){
            $response->error('Выберите пользователя или введите электронную почту', 'user_id');
            $response->error('Выберите пользователя или введите электронную почту', 'email');
        }

        if($request->data->get('user_id') and MailingSubscriberQuery::create()->findOneByUserId($request->data->get('user_id'))){
            $response->error('Пользователь уже является подписчиком', 'user_id');
        }

    }

    public function preSave(ComponentRequest $request, ComponentResponse $response, $entity)
    {
        if($request->data->get('user_id')){
            $entity->setTitle('');
            $entity->setEmail('');
        }
    }


}
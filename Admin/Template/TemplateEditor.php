<?php

namespace Creonit\MailingBundle\Admin\Template;

use Creonit\AdminBundle\Component\EditorComponent;
use Creonit\AdminBundle\Component\Request\ComponentRequest;
use Creonit\AdminBundle\Component\Response\ComponentResponse;
use Creonit\MailingBundle\Model\MailingTemplate;
use Creonit\MailingBundle\Templating\Templating;
use Symfony\Component\Mime\Address;

class TemplateEditor extends EditorComponent
{
    /**
     * @var Templating
     */
    protected $templating;

    /**
     * @title Почтовый шаблон
     *
     * @entity Creonit\MailingBundle\Model\MailingTemplate
     *
     * @template
     * {{ title | text | group('Название') }}
     * {{ subject | text | group('Тема') }}
     *
     * {% filter panel('default', 'Отправитель') %}
     *  {% filter row %}
     *      {{ from_email | text | group('Email') | col(6) }}
     *      {{ from_name | text | group('Имя') | col(6) }}
     *  {% endfilter %}
     * {% endfilter %}
     *
     *  {{ template | textarea | group('Содержимое') }}
     */
    public function schema()
    {
    }

    public function __construct(Templating $templating)
    {
        $this->templating = $templating;
    }

    protected function retrieveEntity(ComponentRequest $request, ComponentResponse $response)
    {
        if (!$request->query->has('key')) {
            $response->flushError('Не указан ключ');
        }

        if (!$entity = $this->createQuery()->findPk($request->query->get('key')) ) {
            $entity = new MailingTemplate();
        }

        $this->syncTemplate($request, $response, $entity);

        return $entity;
    }

    protected function syncTemplate(ComponentRequest $request, ComponentResponse $response, MailingTemplate $entity)
    {
        if (!$template = $this->templating->getTemplate($request->query->get('key'))) {
            $response->flushError('Шаблон не найден');
        }

        if ($entity->isNew()) {
            $entity
                ->setSubject($template->getSubject())
                ->setTemplate($template->getTemplate())
                ->setFrom($this->normalizeFrom($template->getFrom()));
        }

        if (!$entity->getKey()) {
            $entity->setKey($template->getKey());
        }

        if (!$entity->getTitle()) {
            $entity->setTitle($template->getTitle());
        }
    }

    protected function normalizeFrom($from)
    {
        $name = '';
        $email = '';

        if (is_string($from)) {
            $email = $from;

        } elseif ($from instanceof Address) {
            $name = $from->getName();
            $email = $from->getAddress();
        }

        return [
            'name' => $name,
            'email' => $email,
        ];
    }

    /**
     * @param ComponentRequest $request
     * @param ComponentResponse $response
     * @param MailingTemplate $entity
     */
    public function decorate(ComponentRequest $request, ComponentResponse $response, $entity)
    {
        if (is_array($from = $entity->getFrom())) {
            $response->data->add([
                'from_email' => $from['email'] ?? '',
                'from_name' => $from['name'] ?? '',
            ]);
        }
    }

    /**
     * @param ComponentRequest $request
     * @param ComponentResponse $response
     * @param MailingTemplate $entity
     */
    public function preSave(ComponentRequest $request, ComponentResponse $response, $entity)
    {
        $requestFrom = [
            'email' => $request->data->get('from_email'),
            'name' => $request->data->get('from_name'),
        ];

        $entity->setFrom($requestFrom);
    }
}
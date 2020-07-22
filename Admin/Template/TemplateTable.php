<?php

namespace Creonit\MailingBundle\Admin\Template;

use Creonit\AdminBundle\Component\Request\ComponentRequest;
use Creonit\AdminBundle\Component\Response\ComponentResponse;
use Creonit\AdminBundle\Component\Scope\ListRowScope;
use Creonit\AdminBundle\Component\Scope\Scope;
use Creonit\AdminBundle\Component\TableComponent;
use Creonit\MailingBundle\Templating\MailingTemplate;
use Creonit\MailingBundle\Templating\Templating;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\Mime\Address;

class TemplateTable extends TableComponent
{
    /**
     * @var Templating
     */
    protected $templating;

    /**
     * @title Шаблоны писем
     * @cols Название, Тема, Отправитель
     *
     * \MailingTemplate
     *
     * @col {{ title | open('TemplateEditor', {key: _key}) }}
     * @col {{ subject }}
     * @col {{ from }}
     */
    public function schema()
    {
    }

    public function __construct(Templating $templating)
    {
        $this->templating = $templating;
    }

    protected function load(ComponentRequest $request, ComponentResponse $response, ListRowScope $scope, $relation = null, $relationValue = null, $level = 0)
    {
        if ($scope->getName() === 'MailingTemplate') {
            $templatesData = [];

            foreach ($this->templating->getTemplates() as $template) {
                $templatesData[] = [
                    '_key' => $template->getKey(),
                    'title' => $template->getTitle(),
                    'subject' => $template->getSubject(),
                    'from' => $this->decorateFrom($template->getFrom()),
                ];
            }

            return $templatesData;
        }

        return parent::load($request, $response, $scope, $relation, $relationValue, $level);
    }

    protected function decorateFrom($from)
    {
        if ($from instanceof Address) {
            return trim("{$from->getName()} {$from->getAddress()}");
        }

        return $from;
    }
}

<?php

namespace Creonit\MailingBundle\Admin;

use Creonit\AdminBundle\Component\Request\ComponentRequest;
use Creonit\AdminBundle\Component\Response\ComponentResponse;
use Creonit\AdminBundle\Component\TableComponent;

class ChannelTable extends TableComponent
{

    /**
     * @title Список каналов
     * @header
     * {{ button('Добавить канал', {size: 'sm', type: 'success', icon: 'bars'}) | open('Mailing.ChannelEditor') }}
     *
     * @cols Название, Идентификатор, '', .
     *
     * @action export(channel){
     *      var component = this;
     *      component.request('export', {channel: channel}, {}, function(response){
     *          if(component.checkResponse(response)){
     *              document.location.href = response.data.file;
     *          }
     *      });
     * }
     * 
     * \MailingChannel
     * @entity Creonit\MailingBundle\Model\MailingChannel
     * @sortable true
     *
     * @col {{ title | icon('bars') | open('Mailing.ChannelEditor', {key: _key}) | controls }}
     * @col {{ name }}
     * @col {{ button('Экспортировать в CSV', {size: 'xs', icon: 'share'}) | action('export', name) }}
     * @col {{ buttons(_visible() ~ _delete()) }}
     *
     */
    public function schema()
    {
        $this->setHandler('export', function(ComponentRequest $request, ComponentResponse $response){
            $cacheDir = '/cache/mailing/';
            $cachePath = $this->container->getParameter('kernel.root_dir') . '/../web' . $cacheDir;
            $filename = time() . uniqid(mt_srand()) . '.csv';
            if(!is_dir($cachePath)){
                mkdir($cachePath, 0777, true);
            }

            $subscribers = $this->container->get('creonit_mailing')->getSubscribers($request->query->get('channel'));

            $handle = fopen($cachePath.$filename, 'w');

            foreach($subscribers as $email => $title){
                fputcsv($handle, [$email, $title]);

            }

            fclose($handle);

            $response->data->set('file', $cacheDir.$filename);
        });
    }
}
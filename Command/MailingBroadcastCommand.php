<?php

namespace Creonit\MailingBundle\Command;

use Creonit\MailingBundle\Model\MailingBroadcast;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MailingBroadcastCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('mailing:broadcast')
            ->setDescription('Send all broadcasts')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mailing = $this->getContainer()->get('creonit_mailing');

        foreach ($mailing->getBroadcasts() as $broadcast){
            $output->write("Sending broadcast #{$broadcast->getId()} ...");

            try{
                if($subscribers = $mailing->getBroadcastSubscribers($broadcast)){
                    $message = $mailing->createMessage($broadcast->getTitle(), $broadcast->getContent());

                    foreach ($subscribers as $email => $title) {
                        $mailing->send($message, [$email => $title]);
                    }
                }

                $broadcast->setStatus(MailingBroadcast::STATUS_SUCCESS)->save();
                $output->writeln(" [success]");


            } catch (\Exception $e){
                $broadcast->setStatus(MailingBroadcast::STATUS_FAIL)->save();
                $output->writeln(" [failed]");

            }

        }
    }

}

<?php
/**
 * Created by PhpStorm.
 * User: ruben.hollevoet
 * Date: 10/06/18
 * Time: 22:32
 */

namespace AppBundle\Event;


use JavierEguiluz\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        // TODO: Implement getSubscribedEvents() method.

        return [
            EasyAdminEvents::PRE_UPDATE => 'onPreUpdate'
        ];
    }

}

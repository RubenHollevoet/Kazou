<?php
/**
 * Created by PhpStorm.
 * User: ruben.hollevoet
 * Date: 07/06/18
 * Time: 21:14
 */

namespace AppBundle\Controller\EasyAdmin;

use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;

//class TripGroupController extends BaseAdminController
//{
//    protected function listTripGroupAction() {
//        $this->entity['list']['dql_filter'] = 'entity.region = '.(string)$this->getUser()->getRegion()->getId();
//
//        return parent::listTripGroupAction();
//    }
//
//    protected function persistTripGroupEntity($entity)
//    {
//        $entity->setRegion($this->getUser()->getRegion());
//
//        parent::persistTripGroupEntity($entity);
//    }
//}

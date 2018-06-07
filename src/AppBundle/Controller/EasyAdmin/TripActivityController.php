<?php
/**
 * Created by PhpStorm.
 * User: ruben.hollevoet
 * Date: 07/06/18
 * Time: 21:14
 */

namespace AppBundle\Controller\EasyAdmin;

use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;

class TripActivityController extends BaseAdminController
{
    protected function listAction() {
        $this->entity['list']['dql_filter'] = 'entity.region = '.(string)$this->getUser()->getRegion()->getId();

        return parent::listAction();
    }

    protected function persistEntity($entity)
    {
        $entity->setRegion($this->getUser()->getRegion());

        parent::persistEntity($entity);
    }
}

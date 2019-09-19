<?php
/**
 * Created by PhpStorm.
 * User: ruben.hollevoet
 * Date: 19/05/18
 * Time: 16:46
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class LinkRepository extends EntityRepository
{
    public function getLinksByWeight()
    {
        return $this->createQueryBuilder('link')
            ->orderBy('link.order', 'ASC')
            ->where('link.enabled = true')
            ->getQuery()
            ->execute();
    }
}

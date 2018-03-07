<?php
/**
 * Created by PhpStorm.
 * User: ruben.hollevoet
 * Date: 06-03-2018
 * Time: 20:31
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="alliance")
 */
class Alliance
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $alliance;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getAlliance()
    {
        return $this->alliance;
    }

    /**
     * @param mixed $alliance
     */
    public function setAlliance($alliance)
    {
        $this->alliance = $alliance;
    }
}

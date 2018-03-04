<?php
/**
 * Created by PhpStorm.
 * User: ruben.hollevoet
 * Date: 04-03-2018
 * Time: 16:55
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="trip_activity")
 */
class TripActivity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="TripGroup", inversedBy="trips")
     * @ORM\JoinColumn()
     */
    private $tripGroup;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     */
    private $code;

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
    public function getTripGroup()
    {
        return $this->tripGroup;
    }

    /**
     * @param mixed $tripGroup
     */
    public function setTripGroup($tripGroup)
    {
        $this->tripGroup = $tripGroup;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }
}

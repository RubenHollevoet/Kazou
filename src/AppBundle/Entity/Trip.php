<?php
/**
 * Created by PhpStorm.
 * User: ruben.hollevoet
 * Date: 22-12-2017
 * Time: 15:30
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TripRepository")
 * @ORM\Table(name="trip")
 */
class Trip
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="trips")
     * @ORM\JoinColumn()
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="TripGroup", inversedBy="trips")
     * @ORM\JoinColumn()
     */
    private $group;

    /**
     * @ORM\ManyToOne(targetEntity="TripActivity", inversedBy="trips")
     * @ORM\JoinColumn()
     */
    private $activity;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $from_;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $to_;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string")
     */
    private $transport_type;

    /**
     * @ORM\Column(type="string")
     */
    private $company;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $distance;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    private $price;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $tickets;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $comment;

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
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param mixed $group
     */
    public function setGroup($group)
    {
        $this->group = $group;
    }

    /**
     * @return mixed
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * @param mixed $activity
     */
    public function setActivity($activity)
    {
        $this->activity = $activity;
    }

    /**
     * @return mixed
     */
    public function getFrom()
    {
        return $this->from_;
    }

    /**
     * @param mixed $from_
     */
    public function setFrom($from_)
    {
        $this->from_ = $from_;
    }

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to_;
    }

    /**
     * @param mixed $to_
     */
    public function setTo($to_)
    {
        $this->to_ = $to_;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getTransportType()
    {
        return $this->transport_type;
    }

    /**
     * @param mixed $transport_type
     */
    public function setTransportType($transport_type)
    {
        $this->transport_type = $transport_type;
    }

    /**
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param mixed $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * @return mixed
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * @param mixed $distance
     */
    public function setDistance($distance)
    {
        $this->distance = $distance;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getTickets()
    {
        return $this->tickets;
    }

    /**
     * @param mixed $tickets
     */
    public function setTickets($tickets)
    {
        $this->tickets = $tickets;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }
}

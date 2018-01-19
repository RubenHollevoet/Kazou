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
     * @ORM\Column(type="string")
     */
    private $from_;

    /**
     * @ORM\Column(type="string")
     */
    private $to_;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $createdAt;

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
     * @ORM\Column(type="string", nullable=true)
     */
    private $amount;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $tickets;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity="TripGroup", inversedBy="trips")
     * @ORM\JoinColumn(nullable=false)
     */
    private $group;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="trips_")
     */
    private $activity;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="trips")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;




    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function getFrom()
    {
        return $this->from_;
    }

    public function setFrom($from_)
    {
        $this->from_ = $from_;
    }

    public function getTo()
    {
        return $this->to_;
    }

    public function setTo($to_)
    {
        $this->to_ = $to_;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
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
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
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
}

<?php
/**
 * Created by PhpStorm.
 * User: ruben.hollevoet
 * Date: 22-12-2017
 * Time: 14:38
 */

namespace AppBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="`user`")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string")
     */
    private $lastName;

    /**
     * @ORM\Column(name="facebook_id", type="string", length=255, nullable=true)
     */
    private $facebook_id;

    /**
     * @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true)
     */
    private $facebook_access_token;

//    /**
//     * @ORM\Column(name="google_id", type="string", length=255, nullable=true)
//     */
//    private $google_id;
//
//    /**
//     * @ORM\Column(name="google_access_token", type="string", length=255, nullable=true)
//     */
//    private $google_access_token;


    /**
     * @ORM\Column(type="string")
     */
    //private $bank;

    /**
     * @ORM\Column(type="integer")
     */
    //private $personId;

    /**
     * @ORM\OneToMany(targetEntity="Trip", mappedBy="user")
     * @ORM\OrderBy({"createdAt"="DESC"})
     */
    private $trips;

    public function __construct()
    {
        parent::__construct();
        $this->trips = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function setEmail($email)
    {
        $this->setUsername($email);

        return parent::setEmail($email);
    }

//
//    /**
//     * @return mixed
//     */
//    public function getBank()
//    {
//        return $this->bank;
//    }
//
//    /**
//     * @param mixed $bank
//     */
//    public function setBank($bank)
//    {
//        $this->bank = $bank;
//    }
//
//    /**
//     * @return mixed
//     */
//    public function getPersonId()
//    {
//        return $this->personId;
//    }
//
//    /**
//     * @param mixed $personId
//     */
//    public function setPersonId($personId)
//    {
//        $this->personId = $personId;
//    }

    public function setFacebookId($facebookID) {
        $this->facebook_id = $facebookID;
    }

    public function getFacebookId() {
        return $this->facebook_id;
    }

    public function setFacebookAccessToken($facebookAccessToken) {
        $this->facebook_access_token = $facebookAccessToken;
    }

    public function getFacebookAccessToken() {
        return $this->facebook_access_token;
    }
//
//    public function setGoogleId($googleID) {
//        $this->google_id = $googleID;
//    }
//
//    public function getGoogleId() {
//        return $this->google_id;
//    }
//
//    public function setGoogleAccessToken($googleAccessToken) {
//        $this->google_access_token = $googleAccessToken;
//    }
//
//    public function getGoogleAccessToken() {
//        return $this->google_access_token;
//    }




    /**
     * @return ArrayCollection|Trip[]
     */
    public function getTrips()
    {
        return $this->trips;
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: ruben.hollevoet
 * Date: 22-12-2017
 * Time: 14:30
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    /**
     * @Route("/user", name="user")
     */
    public function showUser()
    {
        /*$user = null;
        if(isset($_COOKIE['userHash'])) {
            $em = $this->getDoctrine()->getEntityManager();
            $user = $em->getRepository('AppBundle:User')->findOneBy(['hash' => $_COOKIE['userHash']]);
        }
        return $this->render('user/show.html.twig', ['user' => $user]);*/
    }

    /**
     * @Route("/user/add", name="user_add")
     */
    public function addUser()
    {
        $user = new User();
        $user->setFirstName('user'.rand(0,100));
        $user->setLastName(rand(0,100));
        $user->setBank('-');
        $user->setEmail('-');
        $user->setPersonId('-');
       // $user->setHash(sha1($user->getEmail().$user->getBank().rand(1,1000)));

        //temporary
        //setcookie('userHash', $user->getHash(),time()+31556926, '/');

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($user);
        $em->flush();

        return $this->render('user/add.html.twig', ['user' => $user]);
    }

    /**
     * @Route("/user/login", name="user_login")
     */
    public function loginUser()
    {
        return $this->render('user/login.html.twig', []);
    }
}

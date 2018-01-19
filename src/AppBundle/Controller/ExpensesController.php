<?php
/**
 * Created by PhpStorm.
 * User: ruben.hollevoet
 * Date: 22-12-2017
 * Time: 15:43
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Trip;
use AppBundle\Entity\User;
use AppBundle\Repository\TripRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ExpensesController extends Controller
{
    /**
     * @Route("/expenses/add", name="expense_add")
     */
    public function addExpense()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $user = null;
        if( isset($_COOKIE['userHash'])) {
            $user = $em->getRepository('AppBundle:User')->findOneBy(['hash' => $_COOKIE['userHash']]);
        }
        else {
            throw $this->createNotFoundException('no user signed in');
        }

        $group = $em->getRepository('AppBundle:TripGroup')->find(1); //find group with id 1

        if($user) {

            $trip = new Trip();
            $trip->setFrom('A-'.rand(0,100));
            $trip->setTo('B-'.rand(0,100));
            $trip->setCreatedAt(new \DateTime());
            $trip->setDate(new \DateTime('1/1/2018 2:00 pm'));
            $trip->setTransportType('auto');
            $trip->setCompany('alone');

            $trip->setGroup($group);
            $trip->setUser($user);

            $em->persist($trip);

            $em->flush();

            return $this->render('expense/add.html.twig', ['trip' => $trip]);
        }
        else {
            throw $this->createNotFoundException('no user found for the current hash');
        }
    }

    /**
     * @Route("/expenses", name="expense")
     */
    public function showExpense()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $user = null;
        if( isset($_COOKIE['userHash'])) {
            $user = $em->getRepository('AppBundle:User')->findOneBy(['hash' => $_COOKIE['userHash']]);
            if($user) {
                //$trips = $user->getTrips();
                $trips = $em->getRepository('AppBundle:Trip')->findAllRecentTripsForUser($user, 5);
                return $this->render('expense/show.html.twig', ['trips' => $trips]);
            }
            else {
                $this->createNotFoundException('user doesnt exist - go to login page');
            }
        }
        else {
            $this->createNotFoundException('no user signed in');
        }
    }
}

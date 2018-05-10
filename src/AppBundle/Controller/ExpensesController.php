<?php
/**
 * Created by PhpStorm.
 * User: ruben.hollevoet
 * Date: 22-12-2017
 * Time: 15:43
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Trip;
use AppBundle\Entity\TripActivity;
use AppBundle\Entity\TripGroup;
use AppBundle\Entity\User;
use AppBundle\Repository\TripRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ExpensesController extends Controller
{
    /**
     * @Route("/expenses/add", name="expense_add")
     */
    public function addExpense(Request $request)
    {
        $user = $this->getUser();
        $trip = new Trip();

        $form = $this->createFormBuilder($trip)
            ->add('to_', TextType::class)
            ->add('from_', TextType::class)
            ->add('date', DateType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $trip = $form->getData();

            dump($trip); die;

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            // $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($task);
            // $entityManager->flush();

            return $this->redirectToRoute('expense_add'); //todo route to expenses added
        }


        return $this->render('expense/add.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);

//        $em = $this->getDoctrine()->getEntityManager();
//
//        $user = null;
//        if (isset($_COOKIE['userHash'])) {
//            $user = $em->getRepository('AppBundle:User')->findOneBy(['hash' => $_COOKIE['userHash']]);
//        } else {
//            throw $this->createNotFoundException('no user signed in');
//        }
//
//        $group = $em->getRepository('AppBundle:TripGroup')->find(1); //find group with id 1
//
//        if ($user) {
//
//            $trip = new Trip();
//            $trip->setFrom('A-' . rand(0, 100));
//            $trip->setTo('B-' . rand(0, 100));
//            $trip->setCreatedAt(new \DateTime());
//            $trip->setDate(new \DateTime('1/1/2018 2:00 pm'));
//            $trip->setTransportType('auto');
//            $trip->setCompany('alone');
//
//            $trip->setGroup($group);
//            $trip->setUser($user);
//
//            $em->persist($trip);
//
//            $em->flush();
//
//            return $this->render('expense/add.html.twig', ['trip' => $trip]);
//        } else {
//            throw $this->createNotFoundException('no user found for the current hash');
//        }
    }

    /**
     * @Route("/expenses", name="expense")
     */
    public function showExpense()
    {
//        $em = $this->getDoctrine()->getEntityManager();
//
//        $user = null;
//        if (isset($_COOKIE['userHash'])) {
//            $user = $em->getRepository('AppBundle:User')->findOneBy(['hash' => $_COOKIE['userHash']]);
//            if ($user) {
//                //$trips = $user->getTrips();
//                $trips = $em->getRepository('AppBundle:Trip')->findAllRecentTripsForUser($user, 5);
//                return $this->render('expense/show.html.twig', ['trips' => $trips]);
//            } else {
//                $this->createNotFoundException('user doesnt exist - go to login page');
//            }
//        } else {
//            $this->createNotFoundException('no user signed in');
//        }

        //TODO check if IBAN and personId are in user

        return $this->render('expense/show.html.twig', [
            'profileComplete' => false
        ]);
    }


    /* --- API --- */
    /**
     * @Route("/expenses/api/getChildGroups")
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getChildGroups(Request $request)
    {
        $group = [];
        $children = [];

        $groupId = $request->query->get('group');
        if($groupId) {
            $result = $this->getDoctrine()->getRepository(TripGroup::class)->find($groupId);
            if($result) {
                $group = $result->getChildren()->getValues();
            }
        }
        else {
            $group = $this->getDoctrine()->getRepository(TripGroup::class)->findBy(['parent' => null]);
        }


        if($group)
        {
            foreach ($group as $child)
            {
                $children[] = [
                    'id' => $child->getId(),
                    'name' => $child->getName(),
                    'type' => 'group',
                    'code' => $child->getCode()
                ];
            }
        }

        return $this->json([
            'status' => 'ok',
            'data' => $children
        ]);
    }

    /**
     * @Route("/expenses/api/getTripActivities")
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getTripActivities(Request $request)
    {
        $tripGroups = [];
        $error = '';

        $groupId = $request->query->get('group');
        if($groupId) {
            $result = $this->getDoctrine()->getRepository(TripGroup::class)->find($groupId);
            if($result) {
                $tripGroups = $result->getTripActivity()->getValues();
//                $activities = $result->getParent()->getTripActivity()->getValues();
                //todo: include parent trip activities
            }
        }
        else {
            $error = 'group doesn\'t exist';
        }

        if(count($tripGroups) < 1) {
            $error = 'no groups exist on this activity';
        }

        if($tripGroups)
        {
            foreach ($tripGroups as $child)
            {
                $activities[] = [
                    'id' => $child->getId(),
                    'name' => $child->getName(),
                    'type' => 'activity',
                    'code' => $child->getCode()
                ];
            }
        }

        return $this->json([
            'status' => $error ? 'error' : 'ok',
            'data' => $error ?: $activities
        ]);
    }

    /**
     * @Route("/expenses/api/createTrip")
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function addTrip(Request $request)
    {
        $formData = json_decode($request->getContent());
        $em = $this->getDoctrine()->getManager();

        //handle user
        $user = null;
        if($formData->userData) { //todo: update check to check here weather a user is loged in or not
            $user = $this->getDoctrine()->getRepository(User::class)->findOneByEmail($formData->userData->email);
            if($user === null)
            {
                //create new user
                $user = new User();
                $user->setEmail($formData->userData->email);
            }

            //update user info
            $user->setIban($formData->userData->iban);
            $user->setPersonId($formData->userData->personId);
            $user->setFirstName($formData->userData->name);
            $user->setLastName($formData->userData->name);

            $em->persist($user);
        }
        else {
            //TODO: get user that's curently signed in
        }

        //handle tripGroup
        $tripGroup = $this->getDoctrine()->getRepository(TripGroup::class)->find($formData->tripData->groupId);

        //handle tripActivity
        $tripActivity = $this->getDoctrine()->getRepository(TripActivity::class)->find($formData->tripData->activityId);

        $tripDate = new \DateTime($formData->tripData->date);

        $trip = new Trip();
        $trip->setUser($user);
        $trip->setFrom($formData->tripData->from);
        $trip->setTo($formData->tripData->to);
        $trip->setDate($tripDate);
//        $trip->setDate('2018-01-01 00:00:00');
        $trip->setGroup($tripGroup);
        $trip->setActivity($tripActivity);
        $trip->setTransportType($formData->tripData->transportType);
        if(property_exists($formData->tripData, 'price')) {
            $trip->setPrice($formData->tripData->price);
        }
        else {
            $trip->setPrice($formData->tripData->distance * 0.25);
        }
        if($formData->tripData->company) $trip->setCompany($formData->tripData->company);
        if($formData->tripData->distance) $trip->setDistance($formData->tripData->distance);
        if($formData->tripData->comment) $trip->setComment($formData->tripData->comment);

        $em->persist($trip);

        $em->flush();

        return $this->json([
            'status' => 'ok',
//            'echo' => json_encode($formData)
        ]);
    }
}

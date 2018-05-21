<?php
/**
 * Created by PhpStorm.
 * User: ruben.hollevoet
 * Date: 22-12-2017
 * Time: 15:43
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Region;
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
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class ExpensesController extends Controller
{
    /**
     * @Route("/expenses", name="expense")
     */
    public function showExpense()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        if (!$user) {
            $_SESSION['_sf2_attributes']['_security.main.target_path'] = $this->generateUrl('expense');
        }

        $trips = $em->getRepository(Trip::class)->findBy(['user' => $user]);

        return $this->render('expense/show.html.twig', [
            'trips' => $trips,
            'fbLoginUrl' => $this->container->get('app.service.facebook_user_provider')->getLoginUrl()
        ]);
    }

    /**
     * @Route("/expenses/add", name="expense_add")
     */
    public function addExpense(Request $request)
    {
        $trip = new Trip();

        $form = $this->createFormBuilder($trip)
            ->add('to_', TextType::class)
            ->add('from_', TextType::class)
            ->add('date', DateType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('expense_add'); //todo route to expenses added
        }


        return $this->render('expense/add.html.twig', [
            'form' => $form->createView(),
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
        if ($groupId) {
            $result = $this->getDoctrine()->getRepository(TripGroup::class)->find($groupId);
            $group = $this->getDoctrine()->getRepository(TripGroup::class)->findBy(['parent' => $result]);
        } else {
            $group = $this->getDoctrine()->getRepository(TripGroup::class)->findBy(['parent' => null]);
        }


        if ($group) {
            foreach ($group as $child) {
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
        $tripActivities = [];
        $error = '';

        $groupId = explode('-', $request->query->get('group'))[0];
        if ($groupId) {
//            $result = $this->getDoctrine()->getRepository(TripGroup::class)->find($groupId);
            $relatedGroupIds = $this->getDoctrine()->getRepository(TripGroup::class)->getParentGroupsById((int)$groupId);
            if ($relatedGroupIds) {
                $tripActivities = $this->getDoctrine()->getRepository(TripGroup::class)->getActivitiesByGroupArr($relatedGroupIds);
            }
        } else {
            $error = 'group doesn\'t exist';
        }

        if (count($tripActivities) < 1) {
            $error = 'no groups exist on this activity';
        }

        if ($tripActivities) {
            foreach ($tripActivities as $child) {
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
        $ticketsArr = [];

        if($formData->tripData->tickets) {
            $folder = '/uploads/tickets/'.$this->getUser()->getId().'/';
            $uploadPath = $this->getParameter('upload_directory');
            if(!is_dir($uploadPath.$folder))
            {
                mkdir($uploadPath.$folder, 0777, true);
            }

            $loopCount = 0;
            $fileName = hash('md5', microtime(true) + $loopCount).'.'.explode('/', $formData->tripData->tickets->mime )[1];
            file_put_contents($uploadPath.$folder.$fileName, fopen($formData->tripData->tickets->content, 'r'));

            $ticketsArr[] = $folder.$fileName;
        }

        //update user
        $user = $this->getUser();
        if (!$user) {
//            $user = $this->getDoctrine()->getRepository(User::class)->findOneByEmail($formData->userData->email);
//            if ($user === null) {
//                //create new user
//                $user = new User();
//                $user->setRegion($em->getRepository(Region::class)->find(1));
//            }
            //TODO: throw exception

            return $this->json([
                'status' => '500',
                'data' => 'user not signed in'
            ]);
        }

        //update user info
        $nameArr = explode(' ', $formData->userData->name);
        $user->setFirstName($nameArr[0]);
        array_shift($nameArr);
        $user->setLastName(implode($nameArr, ' '));
        $user->setEmail($formData->userData->email);
        $user->setIban($formData->userData->iban);
        $user->setPersonId($formData->userData->personId);

        $em->persist($user);

        //handle tripGroup
        $tripGroup = $this->getDoctrine()->getRepository(TripGroup::class)->find($formData->tripData->groupId);

        //handle tripActivity
        $tripActivity = $this->getDoctrine()->getRepository(TripActivity::class)->find($formData->tripData->activityId);

        $tripDate = new \DateTime($formData->tripData->date);

        $trip = new Trip();
        $trip->setRegion($em->getRepository(Region::class)->find(1)); //attach Kazou region
        $trip->setUser($user);
        $trip->setFrom($formData->tripData->from);
        $trip->setTo($formData->tripData->to);
        $trip->setDate($tripDate);
        $trip->setGroup($tripGroup);
        $trip->setActivity($tripActivity);
        $trip->setTransportType($formData->tripData->transportType);
        if (property_exists($formData->tripData, 'price')) {
//            $trip->setPrice($formData->tripData->price);
            $trip->setPrice(100);
        } else {
            $trip->setPrice($formData->tripData->distance * 0.25);
        }
        if($ticketsArr) $trip->setTickets($ticketsArr);
        if($formData->tripData->company) $trip->setCompany($formData->tripData->company);
        if($formData->tripData->distance) $trip->setDistance($formData->tripData->distance);
        if($formData->tripData->comment) $trip->setComment($formData->tripData->comment);

        $em->persist($trip);

        $em->flush();

        return $this->json([
            'status' => 'ok',
        ]);
    }
}

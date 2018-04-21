<?php
/**
 * Created by PhpStorm.
 * User: ruben.hollevoet
 * Date: 22-12-2017
 * Time: 14:30
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use AppBundle\Form\UserRegistrationForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * @Route("/user/facebookResponse")
     */
    public function loginResponse() {

        $fbUserProvider = $this->container->get('app.service.facebook_user_provider');
        $fbUserProvider->handleResponse();


        return new Response('');
    }

    /**
     * @Route("/user/profile")
     */
    public function showProfile()
    {
        return new Response(
            '<html><body>page--</body></html>'
        );

    }

    /**
     * @Route("/user/register", name="user_register")
     */
    public function registerUser(Request $request)
    {



        // form stuff
        $form = $this->createForm(UserRegistrationForm::class);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $user = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Welkom '.$user->getEmail() . '!');
            return $this->redirectToRoute('homepage');
        }

        return $this->render('user/register.html.twig', [
            'form' => $form->createView(),
            'fbLoginUrl' => $this->container->get('app.service.facebook_user_provider')->getLoginUrl()
        ]);
    }

    /**
     * @Route("/user/login", name="user_login")
     */
    public function loginUser()
    {
        return $this->render('user/login.html.twig', [

            ]);
    }
}

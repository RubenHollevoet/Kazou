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
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
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


        $fb = new Facebook([
            'app_id' => $this->getParameter('facebook_app_id'), // Replace {app-id} with your app id
            'app_secret' => $this->getParameter('facebook_app_secret'),
            'default_graph_version' => 'v2.2',
        ]);

        $helper = $fb->getRedirectLoginHelper();

        $permissions = ['email']; // Optional permissions
        $loginUrl = $helper->getLoginUrl($this->getParameter('facebook_oauth_redirect'), $permissions);

        return new Response('<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a> ');

    }

    /**
     * @Route("/user/login/response")
     */
    public function loginResponse() {
        $fb = new Facebook([
            'app_id' => $this->getParameter('facebook_app_id'), // Replace {app-id} with your app id
            'app_secret' => $this->getParameter('facebook_app_secret'),
            'default_graph_version' => 'v2.2',
        ]);

        $helper = $fb->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken($this->getParameter('facebook_oauth_redirect'));
        } catch(FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        if (! isset($accessToken)) {
            if ($helper->getError()) {
                header('HTTP/1.0 401 Unauthorized');
                echo "Error: " . $helper->getError() . "\n";
                echo "Error Code: " . $helper->getErrorCode() . "\n";
                echo "Error Reason: " . $helper->getErrorReason() . "\n";
                echo "Error Description: " . $helper->getErrorDescription() . "\n";
            } else {
                header('HTTP/1.0 400 Bad Request');
                echo 'Bad request';
            }
            exit;
        }

        // Logged in
        echo '<h3>Access Token</h3>';
        var_dump($accessToken->getValue());

        // The OAuth 2.0 client handler helps us manage access tokens
        $oAuth2Client = $fb->getOAuth2Client();

        // Get the access token metadata from /debug_token
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);
        echo '<h3>Metadata</h3>';
        var_dump($tokenMetadata);
        // Validation (these will throw FacebookSDKException's when they fail)
        $tokenMetadata->validateAppId($this->getParameter('facebook_app_id')); // Replace {app-id} with your app id
        // If you know the user ID this access token belongs to, you can validate it here
        //$tokenMetadata->validateUserId('123');
        $tokenMetadata->validateExpiration();

        if (! $accessToken->isLongLived()) {
            // Exchanges a short-lived access token for a long-lived one
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch (FacebookSDKException $e) {
                echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
                exit;
            }

            echo '<h3>Long-lived</h3>';
            var_dump($accessToken->getValue());
        }

        $this->get('session')->set('fb_access_token', (string) $accessToken);

        // User is logged in with a long-lived access token.
        // You can redirect them to a members-only page.
        //header('Location: https://example.com/members.php');

        try {
            // Returns a `Facebook\FacebookResponse` object
            $fb_response = $fb->get('/me?fields=id,name,email,picture.width(800).height(800).redirect(0)', $this->get('session')->get('fb_access_token'));
        } catch(FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $user = $fb_response->getGraphUser();
        dump($user);
        die();

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
//        $user = new User();
//        $user->setFirstName('user' . rand(0, 100));
//        $user->setLastName(rand(0, 100));
//        $user->setBank('-');
//        $user->setEmail('-');
//        $user->setPersonId('-');
//        // $user->setHash(sha1($user->getEmail().$user->getBank().rand(1,1000)));
//
//        //temporary
//        //setcookie('userHash', $user->getHash(),time()+31556926, '/');
//
//        $em = $this->getDoctrine()->getEntityManager();
//        $em->persist($user);
//        $em->flush();

        $form = $this->createForm(UserRegistrationForm::class);

        $form->handleRequest($request);
        if ($form->isValid()) {
            /** @var User $user */
            $user = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Welcome '.$user->getEmail());
            return $this->redirectToRoute('homepage');
        }

        return $this->render('user/register.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/user/login", name="user_login")
     */
    public function loginUser()
    {
        return $this->render('user/login.html.twig', []);
    }
}

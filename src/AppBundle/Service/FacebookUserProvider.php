<?php
/**
 * Created by PhpStorm.
 * User: ruben.hollevoet
 * Date: 21/04/18
 * Time: 08:40
 */

namespace AppBundle\Service;


use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Router;

class FacebookUserProvider
{
    private $param_facebook_oauth_redirect;
    private $param_facebook_app_id;
    private $param_facebook_app_secret;
    /**
     * @var Session
     */
    private $session;
    /**
     * @var Router
     */
    private $router;


    /**
     * FacebookUserProvider constructor.
     */
    public function __construct(Session $session, Router $router, $param_facebook_oauth_redirect, $param_facebook_app_id, $param_facebook_app_secret)
    {
        $this->param_facebook_oauth_redirect = $param_facebook_oauth_redirect;
        $this->param_facebook_app_id = $param_facebook_app_id;
        $this->param_facebook_app_secret = $param_facebook_app_secret;
        $this->session = $session;
        $this->router = $router;
    }

    public function handleResponse()
    {
        die('----- 555 -----');

        $fb = $this->getFacebook();

        $helper = $fb->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken($this->param_facebook_oauth_redirect);
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
        $tokenMetadata->validateAppId($this->param_facebook_app_id); // Replace {app-id} with your app id
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

        $this->session->set('fb_access_token', (string) $accessToken);

//        return new RedirectResponse($this->router->generate('homepage'));
    }

    public function getCurrentUser()
    {
        $fb_access_token = $this->session->get('fb_access_token');
        if($fb_access_token)
        {
            $fb = $this->getFacebook();

            try {
                // Returns a `Facebook\FacebookResponse` object
                $fb_response = $fb->get('/me?fields=id,name,email,picture.width(800).height(800).redirect(0)', $this->session->get('fb_access_token'));
            } catch(FacebookResponseException $e) {
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            } catch(FacebookSDKException $e) {
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }

            $fbUser = $fb_response->getGraphUser();

            return $fbUser;
        }
        else
        {
            return;
        }

    }

    public function getLoginUrl()
    {
        $fb = $this->getFacebook();
        $helper = $fb->getRedirectLoginHelper();
        $permissions = ['email']; // Optional permissions
        return $helper->getLoginUrl($this->param_facebook_oauth_redirect, $permissions);
    }

    private function getFacebook() {
        $fb = new Facebook([
            'app_id' => $this->param_facebook_app_id,
            'app_secret' => $this->param_facebook_app_secret,
            'default_graph_version' => 'v2.2',
        ]);

        return $fb;
    }
}

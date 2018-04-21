<?php
/**
 * Created by PhpStorm.
 * User: ruben.hollevoet
 * Date: 17/04/18
 * Time: 18:24
 */

namespace AppBundle\Security;


use AppBundle\Form\LoginForm;
use AppBundle\Service\FacebookUserProvider;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;

class FacebookFormAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    private $formFactory;
    private $em;
    private $router;
    /**
     * @var FacebookUserProvider
     */
    private $facebookUserProvider;

    public function __construct(FormFactoryInterface $formFactory, EntityManager $em, RouterInterface $router, FacebookUserProvider $facebookUserProvider)
    {
        $this->formFactory = $formFactory;
        $this->em = $em;
        $this->router = $router;
        $this->facebookUserProvider = $facebookUserProvider;
    }

    public function getCredentials(Request $request)
    {
        $fbUser = $this->facebookUserProvider->getCurrentUser();

        return $fbUser;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $fbUserId = $credentials['id'];

        return $this->em->getRepository('AppBundle:User')
            ->findOneBy(['fb_userId' => $fbUserId]);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    protected function getLoginUrl()
    {
        return $this->router->generate('security_login');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // if the user hits a secure page and start() was called, this was
        // the URL they were on, and probably where you want to redirect to
        $targetPath = $this->getTargetPath($request->getSession(), $providerKey);

        if (!$targetPath) {
            $targetPath = $this->router->generate('homepage');
        }

        return new RedirectResponse($targetPath);
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: ruben.hollevoet
 * Date: 22-12-2017
 * Time: 14:30
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Link;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function home()
    {
        $links = $this->getDoctrine()->getRepository(Link::class)->getLinksByWeight();
        return $this->render('home.html.twig', ['links' => $links]);
    }

    /**
     * @Route("/privacy")
     */
    public function showPrivacy()
    {
        return $this->render('privacy.html.twig', []);
    }
}

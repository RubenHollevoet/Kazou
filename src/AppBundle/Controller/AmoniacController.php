<?php
/**
 * Created by PhpStorm.
 * User: ruben.hollevoet
 * Date: 03-03-2018
 * Time: 12:24
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class AmoniacController
 * @Route("amoniac")
 */
class AmoniacController extends Controller
{
    /**
     * @Route("/", name="amoniac")
     */
    public function showAction()
    {
        $json = file_get_contents($this->getParameter('google_API_script_Amoniac'));
        $activityData = json_decode($json);

        $showFull = true;

        return $this->render('Amoniac/index.twig', [
            'activityData' => $activityData,
            'showFull' => $showFull
        ]);
    }
}

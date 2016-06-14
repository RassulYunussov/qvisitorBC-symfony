<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/control")
 */

class SuperAdminController extends Controller
{
    /**
     * @Route("/analytics")
     */
    public function indexAction()
    {
        return $this->render('AppBundle:SuperAdmin:index.html.twig', array(
            // ...
        ));
    }
    
    
    /**
     * @Route("/business-center")
     */
    public function bcAction()
    {
    	return $this->render('AppBundle:SuperAdmin:bc.html.twig', array(
    			// ...
    	));
    }
    
    /**
     * @Route("/admin-bc")
     */
    public function adminAction()
    {
    	return $this->render('AppBundle:SuperAdmin:admin.html.twig', array(
    			// ...
    	));
    }

}

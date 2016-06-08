<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/checkpoint")
 */

class CheckpointController extends Controller
{
    /**
     * @Route("/visitors")
     * @Method("GET")
     */
    public function indexAction()
    {
    	
        return $this->render('AppBundle:Checkpoint:index.html.twig', array(
        		
        ));
    }

    /**
     * @Route("/hotentrance")
     * @Method("GET")
     */
    public function hotentranceAction()
    {
    	 
    	return $this->render('AppBundle:Checkpoint:hotentrance.html.twig', array(
    
    	));
    }
    
    
}

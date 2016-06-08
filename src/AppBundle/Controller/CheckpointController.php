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
     * @Route("/entrance")
     * @Method("GET")
     */
    public function indexAction()
    {
    	
        return $this->render('AppBundle:Checkpoint:entrance.html.twig', array(
        		
        ));
    }
    
    /**
     * @Route("/entrance-registration")
     * @Method("GET")
     */
    public function entranceRegAction()
    {
    	 
    	return $this->render('AppBundle:Checkpoint:entrancereg.html.twig', array(
    
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
    
    /**
     * @Route("/hot-entrance-registration")
     * @Method("GET")
     */
    public function hotEnranceRegAction()
    {
    	 
    	return $this->render('AppBundle:Checkpoint:hotentrancereg.html.twig', array(
    
    	));
    }
    
}

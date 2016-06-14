<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class LeaserController extends Controller
{
	
    /**
     * @Route("/leaserpage")
     * @Method("GET")
     */
    public function indexAction()
    {
    	
        return $this->render('AppBundle:Leaser:leaser.html.twig', array(
        		
        ));
    }
    
	 /**
     * @Route("/visitorlist")
     * @Method("GET")
     */
    public function visitorlistAction()
    {
    	
        return $this->render('AppBundle:Leaser:visitorlist.html.twig', array(
        		
        ));
    }

    /**
     * @Route("/editorder")
     * @Method("GET")
     */
    public function editorderAction()
    {

        return $this->render('AppBundle:Leaser:editorder.html.twig', array(

        ));
    }
    
}

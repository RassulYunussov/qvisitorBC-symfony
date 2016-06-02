<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class CheckpointController extends Controller
{
    /**
     * @Route("/index")
     * @Method("GET")
     */
    public function indexAction()
    {
    	
        return $this->render('AppBundle:Checkpoint:index.html.twig', array(
        		
        ));
    }

}

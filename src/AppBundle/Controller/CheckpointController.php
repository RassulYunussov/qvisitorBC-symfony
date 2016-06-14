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
	 * @Route("/index")
	 * @Method("GET")
	 */
	public function indexAction()
	{
	
		return $this->render('AppBundle:Checkpoint:home.html.twig', array(
	
		));
	}
	
	
	
    /**
     * @Route("/entrance")
     * @Method("GET")
     */
    public function entranceAction()
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
    	$em = $this->getDoctrine()->getManager();
    	
    	$qvHotEntrances = $em->getRepository('AppBundle:qvHotEntrance')->findAll();
    	
    	return $this->render('AppBundle:Checkpoint:hotentrance.html.twig', array(
    			'qvHotEntrances' => $qvHotEntrances,
    	));
    	
    }
    
    /**
     * @Route("/hotentrance/{id}", name="hotentrance")
     * @Method("GET")
     */
    public function hotentranceDetailsAction($id)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$qvHotEntrance = $em->getRepository('AppBundle:qvHotEntrance')->findOneBy(array('id'=>$id));
    	 
    	return $this->render('AppBundle:Checkpoint:hotentrancedetails.html.twig', array(
    			'qvHotEntrance' => $qvHotEntrance,
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

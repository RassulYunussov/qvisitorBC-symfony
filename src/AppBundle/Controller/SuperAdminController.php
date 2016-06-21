<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/control")
 */

class SuperAdminController extends Controller
{
    /**
     * @Route("/analytics")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('AppBundle:SuperAdmin:index.html.twig', array(
            // ...
        ));
    }
    
    
    /**
     * @Route("/admin-creation")
     * @Method("GET")
     */
    public function regAdminAction()
    {
    	return $this->render('AppBundle:SuperAdmin:adminreg.html.twig', array(
    			// ...
    	));
    }
    
    /**
     * @Route("/admin-editing")
     * @Method("GET")
     */
    public function editAdminAction()
    {
    	return $this->render('AppBundle:SuperAdmin:adminedit.html.twig', array(
    			// ...
    	));
    }
    
    /**
     * @Route("/admin-bc")
     * @Method("GET")
     */
    public function adminAction()
    {
    	return $this->render('AppBundle:SuperAdmin:admin.html.twig', array(
    			// ...
    	));
    }
    
    /**
     * @Route("/admin-bc/{id}", name="status")
     * @Method("GET")
     */
    public function hotentranceDetailsAction(Request $request)
    {
    		return $this->render('AppBundle:SuperAdmin:status.html.twig', array(
    		));
    	 
    
    }
    
    

}

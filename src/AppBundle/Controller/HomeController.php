<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class HomeController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
    	

    	$authenticationUtils = $this->get('security.authentication_utils');
    	
    	// get the login error if there is one
    	$error = $authenticationUtils->getLastAuthenticationError();
    	
    	// last username entered by the user
    	$lastUsername = $authenticationUtils->getLastUsername();
    	if($this->get('security.authorization_checker')->isGranted('ROLE_LEASER'))
    		return $this->redirect('/leaserpage');
    	if($this->get('security.authorization_checker')->isGranted('ROLE_CHECKPOINT'))
    		return $this->redirect('/checkpoint/index');
        if($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
    		return $this->redirect('/adminbc/index');
    	if($this->get('security.authorization_checker')->isGranted('ROLE_SA'))
    		return $this->redirect('control/analytics'); 
        return $this->render('AppBundle:Signin:login.html.twig', 	 array(
	            // last username entered by the user
	            'last_username' => $lastUsername,
	            'error'         => $error,
	        ));
    }

    /**
     * @Route("/help")
     */
    public function helpAction()
    {
        return $this->render('AppBundle:Home:help.html.twig', array(
            // ...
        ));
    }

}

<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class PageMenuController extends Controller
{
    /**
     * @Route("/menu")
     */
    public function menuAction()
    {
			
		if($this->get('security.authorization_checker')->isGranted('ROLE_LEASER'))
		
		return $this->render('AppBundle:Pagemenu:leaser_menu.html.twig', array(
        ));
    	if($this->get('security.authorization_checker')->isGranted('ROLE_CHECKPOINT'))
		return $this->render('AppBundle:Pagemenu:checkpoint_menu.html.twig', array(
        ));
		if($this->get('security.authorization_checker')->isGranted('ROLE_SA'))
		return $this->render('AppBundle:Pagemenu:admin_menu.html.twig', array(
        ));
		if($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
		return $this->render('AppBundle:Pagemenu:adminbc_menu.html.twig', array(
        ));
		
		
    }

}

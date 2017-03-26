<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\CustomSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\Proxy\SessionHandlerProxy;

class PageMenuController extends Controller
{
    /**
     * @Route("/menu")
     */
    public function menuAction()
    {
    if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
    {
    	if($this->get('security.authorization_checker')->isGranted('ROLE_LEASER'))
		return $this->render('AppBundle:PageMenu:leaser_menu.html.twig', array(
        ));
    	if($this->get('security.authorization_checker')->isGranted('ROLE_CHECKPOINT'))
		return $this->render('AppBundle:PageMenu:checkpoint_menu.html.twig', array(
        ));
	    if($this->get('security.authorization_checker')->isGranted('ROLE_SA'))
		return $this->render('AppBundle:PageMenu:admin_menu.html.twig', array(
        ));
		if($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
		return $this->render('AppBundle:PageMenu:adminbc_menu.html.twig', array());
	}
		else if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_ANONYMOUS')) {
			return $this->redirectToRoute('/login');
		}
		
    }

}

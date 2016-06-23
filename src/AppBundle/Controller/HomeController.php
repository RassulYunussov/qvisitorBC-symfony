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
    	if($this->get('security.authorization_checker')->isGranted('ROLE_LEASER'))
    		return $this->redirect('/leaserpage');
    	if($this->get('security.authorization_checker')->isGranted('ROLE_CHECKPOINT'))
    		return $this->redirect('/checkpoint/entrance');
        if($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
    		return $this->redirect('/adminbc');
    	if($this->get('security.authorization_checker')->isGranted('ROLE_SA'))
    		return $this->redirect('control/analytics'); 
        return $this->render('AppBundle:Home:index.html.twig', array(
            // ...
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

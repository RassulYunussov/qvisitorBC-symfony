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
        return $this->render('AppBundle:PageMenu:menu.html.twig', array(
            'role'=>"checkpoint"
        ));
    }

}

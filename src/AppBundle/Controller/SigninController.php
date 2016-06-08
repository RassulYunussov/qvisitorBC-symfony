<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SigninController extends Controller
{
    /**
     * @Route("/signin")
     */
    public function signinAction()
    {
        return $this->render('AppBundle:Signin:signin.html.twig', array(
            // ...
        ));
    }

}

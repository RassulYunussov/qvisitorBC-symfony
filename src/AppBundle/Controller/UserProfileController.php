<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UserProfileController extends Controller
{
	
	public function profileMenuAction()
	{
		return $this->render('AppBundle:UserProfile:profilemenu.html.twig', array(
				// ...
		));
	}
	
	
	
    /**
     * @Route("userprofile", name="userprofile")
     */
    public function userProfileAction()
    {
        return $this->render('AppBundle:UserProfile:user_profile.html.twig', array(
            // ...
        ));
    }

	/**
     * @Route("/edit_pass", name="edit_pass")
     */
    public function edit_passAction()
    {
        return $this->render('AppBundle:edit_password:edit_pass.html.twig', array(
		// ...
        ));
    }
}

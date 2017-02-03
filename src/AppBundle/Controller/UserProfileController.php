<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Entity\qvUser;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\qvUserPassport;
class UserProfileController extends Controller
{
	
	public function profileMenuAction(Request $request)
	{
		
		$username = $request->getSession()->get('_username');
		return $this->render('AppBundle:UserProfile:profilemenu.html.twig', array(
				'username'=>$username,
		));
	}
	
	
	
    /**
     * @Route("userprofile", name="userprofile")
     */
    public function userProfileAction()
    {
    	$user = $this->get('security.token_storage')->getToken()->getUser();
		$em=$this->getDoctrine()->getManager();
		$userPassport=$em->getRepository('AppBundle:qvUserPassport')->findOneBy(array('user'=>$user->getId()));
        return $this->render('AppBundle:UserProfile:user_profile.html.twig', array(
        'userPassport'=>$userPassport,
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

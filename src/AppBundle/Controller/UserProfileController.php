<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\CustomSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\Proxy\SessionHandlerProxy;
use AppBundle\Form\ChangePasswordType;
use AppBundle\Entity\qvUser;
use AppBundle\Entity\qvUserPassport;
class UserProfileController extends Controller
{
	
	public function profileMenuAction(Request $request)
	{
		$username = $this->get('security.token_storage')->getToken()->getUsername();
        
        if($this->get('security.authorization_checker')->isGranted('ROLE_CHECKPOINT')){
            $session = $this->get("session");
        
        $checkpoint = $session->get('checkpoint');
        $building = $session->get('building');
        return $this->render('AppBundle:UserProfile:profileMenuCheckpoint.html.twig', array(
            'username'=>$username,
            'checkpoint'=> $checkpoint,
            'building'=>$building
        ));}
		return $this->render('AppBundle:UserProfile:profilemenu.html.twig', array(
				'username'=>$username,
		));
	}
	
     /**
     * @Route("/changepassword", name="changepassword")
     * @Method("GET")
     */
	 public function changePasswdAction(Request $request)
    {
    
      return $this->render('AppBundle:UserProfile:changepass.html.twig', array(
          //'form' => $form->createView(),
      ));      
    }
	
    /**
     * @Route("/userprofile", name="userprofile")
     * @Method("GET")
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
}



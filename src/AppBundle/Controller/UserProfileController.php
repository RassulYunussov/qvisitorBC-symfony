<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\CustomSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\Proxy\SessionHandlerProxy;
use AppBundle\Form\ChangePasswordType;
use AppBundle\Form\ChangePassword\ChangePassword;
use AppBundle\Entity\qvUser;


class UserProfileController extends Controller
{

	public function profileMenuAction()
	{
         $user = $this->get('security.token_storage')->getToken()->getUser();
         
        return $this->render('AppBundle:UserProfile:profilemenu.html.twig', array(
		'user'=>$user,
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
       return $this->render('AppBundle:UserProfile:user_profile.html.twig', array(
     ));
    }

	

}



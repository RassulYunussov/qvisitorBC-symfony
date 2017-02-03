<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
<<<<<<< HEAD
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

=======
use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Entity\qvUser;
>>>>>>> e6257a5f53d28238e0e4da5fee503a3044551742

use AppBundle\Entity\qvUserPassport;
class UserProfileController extends Controller
{

	public function profileMenuAction()
	{
<<<<<<< HEAD
         $user = $this->get('security.token_storage')->getToken()->getUser();
         
        return $this->render('AppBundle:UserProfile:profilemenu.html.twig', array(
		'user'=>$user,
=======

		$user = $this->get('security.token_storage')->getToken()->getUser();
		$em=$this->getDoctrine()->getManager();
		$userPassport=$em->getRepository('AppBundle:qvUserPassport')->findOneBy(array('user'=>$user->getId()));
		return $this->render('AppBundle:UserProfile:profilemenu.html.twig', array(
					'userPassport'=>$userPassport,
>>>>>>> e6257a5f53d28238e0e4da5fee503a3044551742
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
<<<<<<< HEAD
       return $this->render('AppBundle:UserProfile:user_profile.html.twig', array(
     ));
=======
    	$user = $this->get('security.token_storage')->getToken()->getUser();
		$em=$this->getDoctrine()->getManager();
		$userPassport=$em->getRepository('AppBundle:qvUserPassport')->findOneBy(array('user'=>$user->getId()));
        return $this->render('AppBundle:UserProfile:user_profile.html.twig', array(
        'userPassport'=>$userPassport,
        ));
>>>>>>> e6257a5f53d28238e0e4da5fee503a3044551742
    }

	

}



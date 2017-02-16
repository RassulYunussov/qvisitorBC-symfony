<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\CustomSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\Proxy\SessionHandlerProxy;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Form\ChangePasswordType;
use AppBundle\Entity\qvUser;
use AppBundle\Entity\qvUserPassport;

class UserProfileController extends Controller
{
	
	public function profileMenuAction(Request $request)
	{
		$username = $this->get('security.token_storage')->getToken()->getUsername();
        
        $qvUser = $this->get('security.token_storage')->getToken()->getUser();

        if($this->get('security.authorization_checker')->isGranted('ROLE_CHECKPOINT')){
            $session = $this->get("session");
        $checkpoint = $session->get('checkpoint');
        $building = $session->get('building');
        return $this->render('AppBundle:UserProfile:profileMenuCheckpoint.html.twig', array(
            'username'=>$username,
            'checkpoint'=> $checkpoint,
            'building'=>$building,
            'qvUser' =>$qvUser,
        ));}
		return $this->render('AppBundle:UserProfile:profilemenu.html.twig', array(
				'username'=>$username,
                'qvUser'=>$qvUser,
		));
	}

    /**
    * @Route("/changepassword{{qvUser}}", name="changepassword")
    * @ParamConverter("qvUser", class="AppBundle:qvUser")
    * @Method({"GET", "POST"})
	*/
        public function changepasswordAction(Request $request, qvUser $qvUser)
        {
         $em = $this->getDoctrine()->getManager();
         $data = array();
         
         $form = $this->createFormBuilder($data)
            ->add('password', RepeatedType::class, array(
                'type'=> PasswordType::class,
                'invalid_message'=>'Пароли должны совпадать',
                'label_attr'=> array('class' => 'text-info'),
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
                'first_options'  => array('label' => 'Пароль', 'attr' => array('class'=>'form-control form-input')),
                'second_options' => array('label' => 'Повторите пароль', 'attr' => array('class'=>'form-control form-input'))
                    ))
            ->getForm()
        ;
        $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
            
            $encoder = $this->container->get('security.password_encoder');
            $data = $form->getData();
            $myrole = $qvUser->getRole();
            $mypass = $encoder->encodePassword($qvUser, $data['password']);
            $qvUser->setPassword($mypass);
            $qvUser->setRole($myrole);
            $qvUser->setDisabled('false');

            $em->flush();
            return $this->redirectToRoute('main_page', array());
        }   
            return $this->render('AppBundle:UserProfile:changepass.html.twig', array(
                'form'=>$form->createView(),
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



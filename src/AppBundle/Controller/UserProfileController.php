<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\CustomSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\Proxy\SessionHandlerProxy;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use AppBundle\Form\Model\ChangePassword;
use AppBundle\Form\ChangePasswordType;

use AppBundle\Entity\qvUser;
use AppBundle\Entity\qvUserPassport;

class UserProfileController extends Controller
{
	
	public function profileMenuAction(Request $request)
	{
		$username = $this->get('security.token_storage')->getToken()->getUsername();
        $qvUser = $this->get('security.token_storage')->getToken()->getUser();

        if($this->get('security.authorization_checker')->isGranted('ROLE_CHECKPOINT'))
        {
            $session = $this->get("session");
            $checkpoint = $session->get('checkpoint');
            $building = $session->get('building');
            return $this->render('AppBundle:UserProfile:profileMenuCheckpoint.html.twig', array(
                'username'=>$username,
                'checkpoint'=> $checkpoint,
                'building'=>$building,
                'qvUser' =>$qvUser,
            ));
        }

        if($this->get('security.authorization_checker')->isGranted('ROLE_LEASER'))
        {
            $em = $this->getDoctrine()->getManager();
            $qvLeaser = $qvUser->getLeaser();

            return $this->render('AppBundle:UserProfile:profileMenuLeaser.html.twig', array(
                'username'=>$username,
                'qvLeaser'=> $qvLeaser,
                'qvUser' =>$qvUser,
            ));
        }

		return $this->render('AppBundle:UserProfile:profilemenu.html.twig', array(
				'username'=>$username,
                'qvUser'=>$qvUser,
		));
	}

    
    /**
    * @Route("/changepassword/{{id}}", name="changepassword")
    * @ParamConverter("id", class="AppBundle:qvUser")
    * @Method({"GET", "POST"})
	*/
        public function changepasswordAction(Request $request)
        {
         $em = $this->getDoctrine()->getManager();
         
        $user = $this->get('security.token_storage')->getToken()->getUser();

             $changePasswordModel = new ChangePassword();
              $form = $this->createFormBuilder($changePasswordModel)
->add('oldPassword', PasswordType::class, array(
                'label'=> 'Старый пароль',
                'attr'=> array('class'=>'form-control form-lg-10')))
            ->add('newPassword', RepeatedType::class, array(
                'type'=> PasswordType::class,
                'invalid_message'=>'Пароли должны совпадать',
                'label_attr'=> array('class' => 'text-info'),
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
                'first_options'  => array('label' => 'Новый пароль', 'attr' => array('class'=>'form-control form-input')),
                'second_options' => array('label' => 'Повторите новый пароль', 'attr' => array('class'=>'form-control form-input'))
                    ))
              ->getForm();
     

        $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
       $em->getConnection()->beginTransaction(); // suspend auto-commit
try {   
        $encoder = $this->container->get('security.password_encoder');
        $id = $user->getId();
        $qvUser = $em->getRepository('AppBundle:qvUser')->find($id);
        
            $data = $form->getData();
            $newpass = $encoder->encodePassword($qvUser, $data->getnewPassword());            
            $qvUser->setPassword($newpass);
            $em->flush();
            $this->addFlash('success', 'Пароль удачно изменен!');
              $em->getConnection()->commit();
} catch (Exception $e) {
    $em->getConnection()->rollBack();
    throw $e;
}
        return $this->redirectToRoute('changepassword', array('id'=>$qvUser->getId()));
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

        $id = $em->getRepository('AppBundle:qvUser')->find($user);

		$userPassport=$em->getRepository('AppBundle:qvUserPassport')->findOneBy(array('user'=>$user->getId()));
        $userPhoto=$em->getRepository('AppBundle:qvUserPhoto')->findOneById($id);
        
        return $this->render('AppBundle:UserProfile:user_profile.html.twig', array(
        'userPassport'=>$userPassport,
        'userPhoto'=>$userPhoto,
        'qvUser' => $user,
        ));
    }
}



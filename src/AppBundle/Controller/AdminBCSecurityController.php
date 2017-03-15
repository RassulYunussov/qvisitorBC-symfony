<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Entity\qvLeaser;
use AppBundle\Entity\qvContract;
use AppBundle\Entity\qvFloor;
use AppBundle\Entity\qvSector;
use AppBundle\Entity\qvCheckpoint;
use AppBundle\Entity\qvBuilding;
use AppBundle\Entity\qvUserPassport;
use AppBundle\Entity\qvUser;
use AppBundle\Entity\qvRole;

/**
 * AdminBCController 
 * 
 * @Route("/adminbc/security")
 * @Security("has_role('ROLE_ADMIN')")
 */
class AdminBCSecurityController extends Controller
{
	 /**
     * Lists all qvUserPassport entities.
     *
     * @Route("/listOfSecurity", name="security_list")
     * @Method("GET")
     */
     public function indexSecurityAction()
    {
        $em1 = $this->getDoctrine()->getManager();
        $em = $this->getDoctrine()->getEntityManager();
        
        $qvUsers = $em->createQuery(
            'SELECT u  FROM AppBundle:qvUser u JOIN u.role r 
            where r.code = :code')->setParameter('code', "ROLE_CHECKPOINT")->getResult();
        
            $query = $em->createQuery(
                'SELECT passport FROM AppBundle:qvUserPassport passport JOIN passport.user pu join pu.role r WHERE r.code = :name'
                    )->setParameter('name', 'ROLE_CHECKPOINT');

            $usp = $query->getResult();

        return $this->render('AppBundle:AdminBC:checkpoints_control/security_man/index.html.twig', array(
            'qvUser' => $qvUsers,
            'usp' => $usp,
        ));
    }

    /**
     * Creates a new UserAccount entity.
     *
     * @Route("/new", name="new_security")
     * @Method({"GET", "POST"})
     */
    public function newSecurityAction(Request $request)
    {
        $qvUser = new qvUser();
        $qvUserPassport = new qvUserPassport();

        $em = $this->getDoctrine()->getManager();
        $em->getConnection()->beginTransaction(); 
        try {
         $data = array();

         $form = $this->createFormBuilder($data)
            ->add('login', TextType::class, array(
                'label'=>'Логин',
                'attr' => array('class'=>'form-control form-input')))
            ->add('password', RepeatedType::class, array(
                'type'=> PasswordType::class,
                'invalid_message'=>'Пароли должны совпадать',
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
                'first_options'  => array('label' => 'Пароль', 'attr' => array('class'=>'form-control form-input')),
                'second_options' => array('label' => 'Повторите пароль', 'attr' => array('class'=>'form-control form-input'))))
            ->add('firstname', TextType::class , array(
                'label'=> 'Имя',
                'attr' => array('class'=>'form-control form-input')))
            ->add('lastname', TextType::class, array(
                'label'=> 'Фамилия',
                'attr' => array('class'=>'form-control form-input')))
            ->add('patronimic', TextType::class, array(
                'label'=> 'Отчество',
                'attr' => array('class'=>'form-control form-input')))
            ->add('birthdate', BirthdayType::class, array(
                'label'=> 'Дата рождения',
                'widget'=>'single_text',
                'attr' => array(
                'class'=>'form-control form-input')))
            ->add('gender',  EntityType::class, array(
                'label'=> 'Пол',
                'class' => 'AppBundle\Entity\qvGender',
                'attr' => array('class'=>'form-control form-input')))
            
            ->getForm()
        ;
        $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
          
           $em = $this->getDoctrine()->getManager();

           $myrole = $em->createQuery('SELECT role from AppBundle:qvRole role WHERE role.code = :name')->setParameter('name', 'ROLE_CHECKPOINT')->getSingleResult();


           $data = $form->getData();
            $encoder = $this->container->get('security.password_encoder');
            $mypass = $encoder->encodePassword($qvUser, $data['password']);
            $qvUser->setLogin($data['login']);   
            $qvUser->setPassword($mypass);
            $qvUser->setRole($myrole);
            $qvUser->setDisabled('false');

            $em->persist($qvUser);
            $em->flush();

            $qvUserPassport->setFirstname($data['firstname']);
            $qvUserPassport->setLastname($data['lastname']);
            $qvUserPassport->setPatronimic($data['patronimic']);
            $qvUserPassport->setBirthdate($data['birthdate']);
            $qvUserPassport->setGender($data['gender']);
            $qvUserPassport->setUser($qvUser);
            
            $em->persist($qvUserPassport);
            $em->flush();
            $em->getConnection()->commit();
            return $this->redirectToRoute('security_list', array());
        }
    }
            catch (Exception $e){
                $em->getConnection()->rollBack();
                throw $e;
            }
        return $this->render('AppBundle:AdminBC:checkpoints_control/security_man/new.html.twig', array(
            'form' => $form->createView(),
        ));   

    }

    /**
     * Finds and displays a qvUserPassport entity.
	 * @ParamConverter("qvUser", class="AppBundle:qvUser")
     * @Route("/{qvUser}/security/{id}/show", name="show_security")
     * @Method("GET")
     */
    public function showSecurityAction(qvUser $qvUser, qvUserPassport $qvUserPassport)
    {
        $em = $this->getDoctrine()->getManager();
        $deleteForm = $this->createDeleteSecurityForm($qvUser, $qvUserPassport);

        $res = $qvUser->getDisabled();
        if($res == 1)
        {
            return $this->render('AppBundle:AdminBC:error.html.twig', array('qvUser'=>$qvUser));
        }
    	else {
        return $this->render('AppBundle:AdminBC:checkpoints_control/security_man/show.html.twig', array(
            'qvUserPassport' => $qvUserPassport,
            'qvUser' => $qvUser,
            'delete_form' => $deleteForm->createView(),
        ));
    }
}
    /**
     * Displays a form to edit an existing qvUserPassport entity.
     * @ParamConverter("qvUser", class="AppBundle:qvUser")
     * @Route("/{qvUser}/security/{id}/edit", name="edit_security")
     * @Method({"GET", "POST"})
     */
    public function editSecurityAction(Request $request, qvUser $qvUser, qvUserPassport $qvUserPassport)
    {
       // $deleteForm = $this->createDeleteSecurityForm($qvUserPassport);
          $em = $this->getDoctrine()->getManager();
        $em->getConnection()->beginTransaction(); // suspend auto-commit
try {
        $res = $qvUser->getDisabled();
        if($res == 1)
        {
            return $this->render('AppBundle:AdminBC:error.html.twig', array('qvUser'=>$qvUser));
        }
    else{
        $editForm = $this->createFormBuilder($qvUserPassport)
            ->add('firstname', TextType::class, array(
                 'label'=> 'Имя',
                 'attr' => array('class'=>'form-control form-input')))
            ->add('lastname',  TextType::class, array(
                 'label'=> 'Фамилия',
                 'attr' => array('class'=>'form-control form-input')))
            ->add('patronimic', TextType::class,array(
                 'label'=> 'Отчество',
                 'attr' => array('class'=>'form-control form-input')))
            ->add('birthdate', BirthdayType::class, array(
                'placeholder' => array(
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                ),
                 'label'=> 'Дата рождения',
                 'attr' => array('class'=>'form-control form-input')))
            ->add('gender',  EntityType::class, array(
                'class' => 'AppBundle\Entity\qvGender',
                'attr'=> array('class'=>'form-control'),
                'label'=>'Пол',)
            )
            ->getForm();
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {  
            
            $em->flush();
            $em->getConnection()->commit();
            
            return $this->redirectToRoute('security_list', array('id' => $qvUserPassport->getId()));       
}    
} 
}
    catch (Exception $e) {
    $em->getConnection()->rollBack();
    throw $e;
}
            
        return $this->render('AppBundle:AdminBC:checkpoints_control/security_man/edit.html.twig', array(
            'qvUserPassport' => $qvUserPassport,
            'edit_form' => $editForm->createView(),
           // 'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a qvUserPassport entity.
     *
     * @Route("/{qvUser}/security/{id}/delete", name="delete_security")
     * @Method("DELETE")
     */
    public function deleteSecurityAction(Request $request, qvUser $qvUser, qvUserPassport $qvUserPassport)
    {
        $em = $this->getDoctrine()->getManager();
        $em->getConnection()->beginTransaction(); 
        try {
        $form = $this->createDeleteSecurityForm($qvUser,$qvUserPassport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        
            $em->remove($qvUser);
            $em->flush();
            $em->remove($qvUserPassport);
            $em->flush();
             $em->getConnection()->commit();
         return $this->redirectToRoute('security_list');
         }
     }
    catch (Exception $e) {
            $em->getConnection()->rollBack();
    throw $e;
}
 }

    /**
     * Creates a form to delete a qvUserPassport entity.
     *
     * @param qvUserPassport $qvUserPassport The qvUserPassport entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteSecurityForm(qvUser $qvUser, qvUserPassport $qvUserPassport)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('delete_security', array('qvUser'=>$qvUser->getId(),'id' => $qvUserPassport->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

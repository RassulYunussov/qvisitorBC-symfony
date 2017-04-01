<?php

namespace AppBundle\Controller\AdminBCControllers;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
use AppBundle\Entity\qvUserPhoto;
use AppBundle\Form\qvUserPhotoType;
/**
 * LeasersController 
 * 
 * @Route("/adminbc/leasers")
 * @Security("has_role('ROLE_ADMIN')")
 */
class LeasersController extends Controller
{
	/**
     * @Route("/listOfLeasers", name="leasers_list")
     * 
     * @Method("GET")
     */
    public function leasersAction()
    {
        $em = $this->getDoctrine()->getManager();

        $qvLeasers = $em->getRepository('AppBundle:qvLeaser')->getLeasersDetailedRaw();

        $qvUsers = $em->createQuery(
            'SELECT u FROM AppBundle:qvUser u  JOIN u.role r 
            where r.code = :code and u.leaser is not null')->setParameter('code', "ROLE_LEASER")->getResult();

        
        return $this->render('AppBundle:AdminBC:leasers_control/leasers/leasers_list.html.twig', array(
        'qvUsers' => $qvUsers,
        'qvLeasers' => $qvLeasers
        ));
    }
    
     /**
     * Creates a new qvLeaser entity.
     *
     * @Route("/new", name="leasers_create")
     * @Method({"GET", "POST"})
     */
    public function createLeaserAction(Request $request)
    {
       $qvLeaser = new qvLeaser();
       $qvUser = new qvUser();
       $qvUserPassport = new qvUserPassport();
       $qvUserPhoto = new qvUserPhoto();
       $em = $this->getDoctrine()->getManager();
       
       $em->getConnection()->beginTransaction(); 
try {
         $data = array();

         $form = $this->createFormBuilder($data)
            ->add('name', TextType::class, array(
                'label'=>'Название компании',
                'attr' => array('class'=>'form-control form-input')))
            ->add('bin', NumberType::class, array(
                'label'=>'БИН',
                'attr' => array('class'=>'form-control form-input')))
            ->add('login', TextType::class, array(
                'label'=>'Логин',
                'attr' => array('class'=>'form-control form-input')))
            ->add('password', RepeatedType::class, array(
                'type'=> PasswordType::class,
                'invalid_message'=>'Пароли должны совпадать',
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
                'first_options'  => array('label' => 'Пароль', 'attr' => array('class'=>'form-control form-input')),
                'second_options' => array('label' => 'Повторите пароль', 'attr' => array('class'=>'form-control form-input'))
                    ))
            ->add('firstname', TextType::class, array(
                'label'=>'Фамилия',
                'attr' => array('class'=>'form-control form-input')))
            ->add('lastname', TextType::class, array(
                'label'=>'Имя',
                'attr' => array('class'=>'form-control form-input')))
            ->add('patronimic', TextType::class, array(
                'label'=>'Отчество',
                'attr' => array('class'=>'form-control form-input')))
            ->add('birthdate', BirthdayType::class, array(
                'label'=> 'Дата рождения',
                'widget'=>'single_text',
                'attr' => array(
                'class'=>'form-control form-input')))
            ->add('gender',  EntityType::class, array(
                'label'=>'Выберите пол',
                'class' => 'AppBundle\Entity\qvGender',
                'attr' => array('class'=>'form-control form-input')
            ))
            ->add('photo', FileType::class, array('label' => 'Ваше фото'))
            ->add('photodate', DateTimeType::class, array(
    'placeholder' => array(
        'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
        'hour' => 'Hour', 'minute' => 'Minute', 'second' => 'Second',
    )
))
            ->getForm()
        ;
        $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
          
           $em = $this->getDoctrine()->getManager();   
           
           $myrole = $em->createQuery('SELECT role from AppBundle:qvRole role WHERE role.code = :name')->setParameter('name', 'ROLE_LEASER')->getSingleResult();
           $encoder = $this->container->get('security.password_encoder');
           $data = $form->getData();
            
            $qvLeaser->SetName($data['name']);
            $qvLeaser->SetBin($data['bin']);
            
            $em->persist($qvLeaser);
            $em->flush();

            $qvUser->setLogin($data['login']);   
            $mypass = $encoder->encodePassword($qvUser, $data['password']);
            $qvUser->setPassword($mypass);
            $qvUser->setRole($myrole);
            $qvUser->setLeaser($qvLeaser);
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

            // $file stores the uploaded jpeg file
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $data['photo'];

            // Generate a unique name for the file before saving it
            $fileName = $data['lastname'].'.'.$file->guessExtension();

               // Move the file to the directory where brochures are stored
                $file->move(
                $this->getParameter('images_directory'),
                $fileName
            );
            // Update the 'brochure' property to store the PDF file name
            // instead of its contents
            $qvUserPhoto->setPhoto($fileName);//($data['photo']);
            $qvUserPhoto->setPhotodate($data['photodate']);
            $qvUserPhoto->setUser($qvUser);
            $em->persist($qvUserPhoto);
            $em->flush();

             $em->getConnection()->commit();
            return $this->redirectToRoute('leasers_list', array());
        }
    }
    catch (Exception $e) {
    $em->getConnection()->rollBack();
    throw $e;
}
        return $this->render('AppBundle:AdminBC:leasers_control/leasers/create_leaser.html.twig', array(
            'qvLeaser' => $qvLeaser,
            'qvUser' => $qvUser,
            'form' => $form->createView(),
            'qvUserPhoto' => $qvUserPhoto,
        ));
    }
    /**
     * Finds and displays a qvLeaser entity.
     *
     * @Route("/{id}/show", name="leasers_show")
     * @Method("GET")
     */
    public function showLeaserAction(qvLeaser $qvLeaser)
    {
        
        $em = $this->getDoctrine()->getManager();
        $em1 = $this->getDoctrine()->getEntityManager();


         $pquery = $em1->createQuery('SELECT p FROM AppBundle:qvUserPassport p LEFT JOIN p.user u LEFT JOIN u.leaser l WHERE l.id = :id')->setParameter('id', $qvLeaser);
        $qvUserPassport = $pquery->getSingleResult();

        $qvUser = $em->getRepository('AppBundle:qvUser')->findLeaser($qvLeaser);

        $userPassport=$em->getRepository('AppBundle:qvUserPassport')->findOneById($qvLeaser);
       

        $deleteForm = $this->createDeleteLeaserForm($qvUser, $qvUserPassport, $qvLeaser);
        
        $queryContract = $em1->createQuery(
            'SELECT cnr from AppBundle:qvContract cnr WHERE cnr.leaser = :name'
            )->setParameter('name', $qvLeaser);
        $contracts = $queryContract->getResult();
            if (!$contracts)
            {
        $contracts = 'Не найдено ни одного контракта';
            }
        $res = $qvUser->getDisabled();
        if($res == 1)
        {
            return $this->render('AppBundle:AdminBC:error.html.twig', array('qvUser'=>$qvUser));
        }
        else
        return $this->render('AppBundle:AdminBC:leasers_control/leasers/show_leaser.html.twig', array(
            'contracts' => $contracts,
            'qvUser'=>$qvUser,
            'qvUserPassport'=>$qvUserPassport,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    /**
     * Displays a form to edit an existing qvLeaser entity.
        *
     * @Route("/company/{id}/edit", name="company_edit")
     * @Method({"GET", "POST"})
     */
    public function editLeaserAction(Request $request, qvLeaser $qvLeaser)
    {
     $emm = $this->getDoctrine()->getEntityManager();
     $em = $this->getDoctrine()->getManager();
     $em->getConnection()->beginTransaction(); 
try {
     $qvUser = $em->getRepository('AppBundle:qvUser')->findLeaser($qvLeaser);

        $res = $qvUser->getDisabled();
        if($res == 1)
        {
            return $this->render('AppBundle:AdminBC:error.html.twig', array('qvUser'=>$qvUser));
        }
    else{
    $pquery = $emm->createQuery('SELECT p FROM AppBundle:qvUserPassport p LEFT JOIN p.user u LEFT JOIN u.leaser l WHERE l.id = :id')->setParameter('id', $qvLeaser);

    $qvUserPassport = $pquery->getSingleResult();
    
     $editForm = $this->createFormBuilder($qvLeaser)
     ->add('name', TextType::class, array(
        'label'=>'Название компании',
        'attr'=> array('class'=>'form-control form-input')))
     ->add('bin', TextType::class, array(
        'label'=>'BIN',
        'attr'=> array('class'=>'form-control form-input')))
     ->getForm();

        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
    
            $em->persist($qvLeaser);            
            $em->flush();
            $em->getConnection()->commit();
            return $this->redirectToRoute('leasers_list', array('id' => $qvLeaser->getId()));
        }
    }
        }
        catch (Exception $e) {
    $em->getConnection()->rollBack();
    throw $e;
}
        return $this->render('AppBundle:AdminBC:leasers_control/leasers/edit_leaser.html.twig', array(
            'qvUserPassport' => $qvUserPassport,
            'qvLeaser'=>$qvLeaser,
            'qvUser'=>$qvUser,
            'res'=>$res,
        'edit_form' => $editForm->createView(),
        ));
}

 
    /**
     * Displays a form to edit an existing qvLeaser entity.
        *
     * @Route("/{id}/editPersonalData", name="leaser_change")
     * @Method({"GET", "POST"})
     */
    public function changePersonalDataLeaserAction(Request $request, qvLeaser $qvLeaser)
    {
     
     //$deleteForm = $this->createDeleteLeaserForm($qvLeaser);
     $emm = $this->getDoctrine()->getEntityManager();
     $em = $this->getDoctrine()->getManager();
     $em->getConnection()->beginTransaction(); 
try {
    $qvUser = $em->getRepository('AppBundle:qvUser')->findLeaser($qvLeaser);
        $res = $qvUser->getDisabled();
        if($res == 1)
        {
            return $this->render('AppBundle:AdminBC:error.html.twig', array('qvUser'=>$qvUser));
        }
    else{
    $pquery = $emm->createQuery('SELECT p FROM AppBundle:qvUserPassport p LEFT JOIN p.user u LEFT JOIN u.leaser l WHERE l.id = :id')->setParameter('id', $qvLeaser);

    $qvUserPassport = $pquery->getSingleResult();

    $editForm2 = $this->createFormBuilder($qvUserPassport)
     ->add('firstname', TextType::class, array(
        'label'=>'Фамилия',
        'attr'=> array('class'=>'form-control form-input')))
     ->add('lastname', TextType::class, array(
        'label'=>'Имя',
        'attr'=> array('class'=>'form-control form-input')))
     ->add('patronimic', TextType::class, array(
        'label'=>'Отчество',
        'attr'=> array('class'=>'form-control form-input')))
     ->add('birthdate', BirthdayType::class, array(
    'placeholder' => array(
        'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
    ), 'label' => 'Дата рождения', 'attr'=>array('class'=>'form-control')
))
     ->add('gender', EntityType::class, array(
        'class'=>'AppBundle:qvGender',
        'label'=>'Пол',
        'attr'=> array('class'=>'form-control form-input')))
     ->getForm();

        $editForm2->handleRequest($request);
        if ($editForm2->isSubmitted() && $editForm2->isValid()) {

            $em->persist($qvUserPassport);
            $em->flush();
            $em->getConnection()->commit();
        return $this->redirectToRoute('leasers_list', array('id' => $qvLeaser->getId()));
    }
}
}
    catch (Exception $e) {
    $em->getConnection()->rollBack();
    throw $e;
        }

        return $this->render('AppBundle:AdminBC:leasers_control/leasers/change_personaldata_leaser.html.twig', array(
            'qvUserPassport' => $qvUserPassport,
            'qvLeaser'=>$qvLeaser,
            'qvUser'=>$qvUser,
        'edit_form2' => $editForm2->createView(),
       // 'delete_form' => $deleteForm->createView(),
        ));
}
 /**
     * Deletes a qvLeaser entity.
     * @Route("/leaser/{qvUser}/{qvUP}/{qvLeaser}/delete", name="leasers_delete")
     * @Method("DELETE")
     */
    public function deleteLeaserAction(Request $request, qvLeaser $qvLeaser)
    {
        $em = $this->getDoctrine()->getManager();
        $emm = $this->getDoctrine()->getEntityManager();
        $em->getConnection()->beginTransaction(); 
try {
        $qvUser = $em->getRepository('AppBundle:qvUser')->findLeaser($qvLeaser);
         $pquery = $emm->createQuery('SELECT p FROM AppBundle:qvUserPassport p LEFT JOIN p.user u LEFT JOIN u.leaser l WHERE l.id = :id')->setParameter('id', $qvLeaser);
        $qvUserPassport = $pquery->getSingleResult();

        $form = $this->createDeleteLeaserForm($qvUser, $qvUserPassport, $qvLeaser);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        $em = $this->getDoctrine()->getManager();
            $em->remove($qvUser);
            $em->remove($qvLeaser);
            $em->remove($qvUserPassport);
            $em->flush();
            $em->getConnection()->commit();
    return $this->redirectToRoute('leasers_list', array());
}
}
catch (Exception $e) {
    $em->getConnection()->rollBack();
    throw $e;
}
}

   
    /**
     * Creates a form to delete a qvLeaser entity.
        *
     * @param qvLeaser $qvLeaser The qvLeaser entity
        *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteLeaserForm(qvUser $qvUser,qvUserPassport $qvUserPassport, qvLeaser $qvLeaser)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('leasers_delete', array('qvUser' => $qvUser->getId(),'qvUP' => $qvUserPassport->getId(), 'qvLeaser' => $qvLeaser->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    

}

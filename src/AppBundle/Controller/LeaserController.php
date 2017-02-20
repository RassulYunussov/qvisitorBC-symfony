<?php

namespace AppBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\qvOrder;
use AppBundle\Entity\qvLeaser;
use AppBundle\Entity\qvUser;
use AppBundle\Entity\qvUserPassport;
use AppBundle\Entity\qvVisitor;
use AppBundle\Entity\qvContract;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Repository\qvOrderTypeRepository;
use AppBundle\Repository\qvGenderRepository;
use AppBundle\Repository\qvOrderRepository;
use AppBundle\Repository\qvVisitorRepository;
use AppBundle\Form\qvVisitorType;


/**
 * @Route("/leaser")
 * @Security("has_role('ROLE_LEASER')")
 */

class LeaserController extends Controller
{
	
    /**
     * @Route("/index", name="index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $em1 = $this->getDoctrine()->getEntityManager();
    	$user = $this->get('security.token_storage')->getToken()->getUser();
        $userPassport=$em->getRepository('AppBundle:qvUserPassport')->findOneBy(array('user'=>$user->getId()));
        $qvContracts = $em->getRepository('AppBundle:qvContract')->findBy(array('leaser'=>$user->getLeaser()));
        $count = $em1->createQuery('SELECT count(contract) from AppBundle:qvContract contract where contract.leaser = :leaser')->setParameter('leaser',$user->getLeaser())->getSingleScalarResult();
        return $this->render('AppBundle:Leaser:index.html.twig', array(
        	'userPassport'=>$userPassport,
            'qvContracts'=>$qvContracts,
            'count' => $count
        ));
    }
    
	 /**
     * @Route("/orders", name="show_orders")
     * @Method("GET")
     */
    public function showOrdersAction()
    {
    	$em = $this->getDoctrine()->getManager();
        $qvOrders=$em->getRepository('AppBundle:qvOrder')->findAll();
        return $this->render('AppBundle:Leaser:orders_list.html.twig', array(
        		'qvOrders'=>$qvOrders,
        ));
    }

    /**
     * @Route("/orders/create-order", name="create_order")
     * @Method({"GET", "POST"})
     */
    public function createOrderAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $data = array();

        $user = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $qvVisitors=$em->getRepository('AppBundle:qvVisitor')->findVisitorByUser($user);
        $form = $this->createFormBuilder($data)
            ->add('sdate', DateType::class, array(
                'label'=>'Дата открытия заявки',
                'widget'=>'single_text',
                'attr'   =>  array(
                'class'   => 'form-margin type_date-inline'))
            )
            ->add('opentime', TimeType::class, array(
                'label'=>'Время открытия заявки',
                'widget'=>'single_text',
                'attr'   =>  array(
                'class'   => 'form-margin type_date-inline'))
            ) 
            ->add('edate', DateType::class, array(                
                'label'=>'Дата закрытия заявки',
                'widget'=>'single_text',
                'attr'   =>  array(
                'class'   => 'form-margin type_date-inline'))
            ) 
            ->add('closetime', TimeType::class, array(
                'label'=>'Время закрытия заявки',
                'widget'=>'single_text',
                'attr'   =>  array(
                'class'   => 'form-margin type_date-inline'))
            ) 
            ->add('ordertype', EntityType::class, array(
                'class' => 'AppBundle:qvOrderType',
                'query_builder' => function (qvOrderTypeRepository $er) {
                        return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },
                'choice_label' => 'name',
                'label'=>'Тип заявки',
                'attr'   =>  array(
                'class'   => 'form-control form-margin')))
            ->add('visitors', EntityType::class, [
                'class'     => 'AppBundle:qvVisitor',
                'query_builder' => function (qvVisitorRepository $repo) {
                    return $repo->createQueryBuilder('f')
                        ->where('f.id >= :id')
                        ->setParameter('id', 1);
                },
                'multiple'  => true
            ])
           ->add('select', ButtonType::class, array(
                    'label'=>'Выбрать', 
                'attr' =>array(
                    'class'=> 'btn btn-default',
                    'data-toggle'=> 'modal',
                    'data-target'=>'#myModal'), ))
            ->getForm()
        ;
    
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $qvOrder = new qvOrder();

            $qvUser = new qvUser();
            $qvUser = $this->get('security.token_storage')->getToken()->getUser();

            $data = $form->getData();
            $qvOrder->setSdate($data['sdate']);
            $qvOrder->setEdate($data['edate']);
            $qvOrder->setOpentime($data['opentime']);
            $qvOrder->setClosetime($data['closetime']);
            $qvOrder->setOrdertype($data['ordertype']);
            $qvOrder->setUser($qvUser);

            foreach ($data['visitors'] as $visitor) {
                
               $qvOrder->addVisitors($visitor);
                }

            $em->persist($qvOrder);
            $em->flush();

    return $this->redirectToRoute('show_orders');
}
        
        return $this->render('AppBundle:Leaser:create_order.html.twig', array(
            'form' => $form->createView(),
            'visitors'=>$qvVisitors
        ));
    }

    /**
     * @Route("/orders/{id}/edit-order", name="edit_order")
     * @Method({"GET", "POST"})
     */
    public function editOrderAction(Request $request, qvOrder $qvOrder)
    {

        $em = $this->getDoctrine()->getManager();

        $user = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $qvVisitors=$em->getRepository('AppBundle:qvVisitor')->getVisitor($user);
        $editForm = $this->createFormBuilder($qvOrder)
            ->add('sdate', DateType::class, array(
                'label'=>'Дата открытия заявки',
                'widget'=>'single_text',
                'attr'   =>  array(
                'class'   => 'form-margin type_date-inline'))
            )
            ->add('opentime', TimeType::class, array(
                'label'=>'Время открытия заявки',
                'widget'=>'single_text',
                'attr'   =>  array(
                'class'   => 'form-margin type_date-inline'))
            ) 
            ->add('edate', DateType::class, array(                
                'label'=>'Дата закрытия заявки',
                'widget'=>'single_text',
                'attr'   =>  array(
                'class'   => 'form-margin type_date-inline'))
            ) 
            ->add('closetime', TimeType::class, array(
                'label'=>'Время закрытия заявки',
                'widget'=>'single_text',
                'attr'   =>  array(
                'class'   => 'form-margin type_date-inline'))
            ) 
            ->add('ordertype', EntityType::class, array(
                'class' => 'AppBundle:qvOrderType',
                'query_builder' => function (qvOrderTypeRepository $er) {
                        return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },
                'choice_label' => 'name',
                'label'=>'Тип заявки',
                'attr'   =>  array(
                'class'   => 'form-control form-margin')))
            ->add('visitors', EntityType::class, [
                'class'     => 'AppBundle:qvVisitor',
                'query_builder' => function (qvVisitorRepository $repo) {
                    return $repo->createQueryBuilder('f')
                        ->where('f.id >= :id')
                        ->setParameter('id', 1);
                },
                'multiple'  => true
            ])
           ->add('select', ButtonType::class, array(
                    'label'=>'Выбрать', 
                'attr' =>array(
                    'class'=> 'btn btn-default',
                    'data-toggle'=> 'modal',
                    'data-target'=>'#myModal'), ))
            ->getForm()
        ;
    
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $qvUser = new qvUser();
            $qvUser = $this->get('security.token_storage')->getToken()->getUser();
            $qvOrder = $editForm->getData();
            $qvOrder->setUser($qvUser);
            $em->persist($qvOrder);
            $em->flush();

    return $this->redirectToRoute('show_orders');
}

        return $this->render('AppBundle:Leaser:edit_order.html.twig', array(
            'visitors'=>$qvVisitors,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a qvOrder entity.
     *
     * @Route("/orders/{id}/delete", name="delete_order")
     * @Method({"GET", "POST"})
     */

    public function deleteAction(Request $request, qvOrder $qvOrder)
    {
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository('AppBundle:qvOrder')->find($qvOrder);
        $em->remove($order);
        $em->flush();
        return $this->redirectToRoute('show_orders');
    }



    /**
     * @Route("/new_visitor", name="new_visitor")
     * @Method({"GET","POST"})
     */
    public function newVisitorAction(Request $request)
    {
        if($request->isXmlHttpRequest()) {

        $em = $this->getDoctrine()->getManager();

        $arr = array();
        $visitorform = $this->createFormBuilder($arr)
            ->add('lastname', TextType::class,array(
                'label'=>'Фамилия',
                'attr'   =>  array(
                'class'   => 'form-margin form-control')))
            ->add('firstname', TextType::class,array(
                'label'=>'Имя',
                'attr'   =>  array(
                'class'   => 'form-margin form-control')))
            ->add('patronimic', TextType::class,array(
                'label'=>'Отчество',
                'attr'   =>  array(
                'class'   => 'form-margin form-control')))
            ->add('birthdate', BirthdayType::class, array(
                'label'=>'Дата рождения',
                'widget'=>'single_text',
                'attr'   =>  array(
                'class'   => 'form-margin type_date-inline form-control')))
            ->add('gender',EntityType::class, array(
                'class' => 'AppBundle:qvGender',
                'query_builder' => function (qvGenderRepository $er) {
                        return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },
                'choice_label' => 'name',
                'label'=>'Пол',
                'attr'   =>  array(
                'class'   => 'form-control form-margin form-control')))
            ->getForm()
            ;

    
        $visitorform->handleRequest($request);

        if ($visitorform->isSubmitted() && $visitorform->isValid()) {
            $qvVisitor = new qvVisitor();

            $arr = $visitorform->getData();
            $qvVisitor->setLastname($arr['lastname']);
            $qvVisitor->setFirstname($arr['firstname']);
            $qvVisitor->setPatronimic($arr['patronimic']);
            $qvVisitor->setBirthdate($arr['birthdate']);
            $qvVisitor->setGender($arr['gender']);
            $em->persist($qvVisitor);
            $em->flush();

            return $this->redirectToRoute('show_orders');
        }

            return $this->render('AppBundle:Leaser:new_visitor.html.twig', array(
                    'visitorform'=>$visitorform->createView()
            ));
       }
    }


    /**
    * @Route("/modal/visitors", name="visitor_modal")
    * @Method("GET")
    */
    public function visitorModalAction(Request $request)
    {
    	if($request->isXmlHttpRequest()){
    		$em=$this->getDoctrine()->getManager();

        	$user = $this->get('security.token_storage')->getToken()->getUser()->getId();
        	$qvVisitors=$em->getRepository('AppBundle:qvVisitor')->findVisitorByUser($user);
    		
    		return $this->render('AppBundle:Leaser:visitor_modal.html.twig', array(
    			'visitors'=>$qvVisitors));

    	}
    }

}

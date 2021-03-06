<?php

namespace AppBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ParameterBag;
use AppBundle\Entity\qvOrder;
use AppBundle\Entity\qvLeaser;
use AppBundle\Entity\qvUser;
use AppBundle\Entity\qvUserPassport;
use AppBundle\Entity\qvVisitor;
use AppBundle\Entity\qvVisitorDoc;
use AppBundle\Entity\qvVisitorPhoto;
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
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
        $data = array();
        $form = $this->createFormBuilder($data)
            ->add('eSelectdate', DateType::class, array(
                'label' => 'Выберите год',
            'years' => range(date('Y'), date('Y')-2)
            ))
            ->add('heSelectdate', DateType::class, array(
                'label' => 'Выберите год',
            'years' => range(date('Y'), date('Y')-2)
            ))
            ->getForm()
        ;
        return $this->render('AppBundle:Leaser:index.html.twig', array(
            'form'=>$form->createView()
        ));
    }

    /**
     * @Route("/index/byyear", name="by-year")
     *@Method({"GET", "POST"})
     */
    public function byYearAction(Request $request)
    { 
        if($request->isXmlHttpRequest()) {

            $em = $this->getDoctrine()->getManager();
            $em1 = $this->getDoctrine()->getEntityManager();
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $year = $request->request->get('year');
            $flag = $request->request->get('flag');

            $data = array();
            $temp = array();
            $result = array();
            $cat = array();
            $em = $this->getDoctrine()->getEntityManager();
            if ($flag == 1) {
                $query=$em->createQuery('SELECT count(e) AS rank, SUBSTRING(e.entrancedate, 6, 2) as month, SUBSTRING(e.entrancedate, 0, 12) as d, COUNT(e.visitor) AS visitorscount FROM AppBundle:qvEntrance e WHERE SUBSTRING(e.entrancedate, 1,4) = :year GROUP BY d order by d')->setParameter('year', $year);
                $data = $query->getResult();
                foreach ($data as $i) {
                $UTC = new \DateTimeZone("UTC");
                $newTZ = new \DateTimeZone("Asia/Almaty");
                $d = new \DateTime($i['d'], $UTC);
                $d->setTimezone( $newTZ );
                $d = $d->format('Y-m-d');
                $a = array($d, intval($i['visitorscount']));
                array_push($temp, $a);
                }
            } elseif ($flag == 2) {
                $query=$em->createQuery('SELECT count(e) AS rank, SUBSTRING(e.entrancedate, 6, 2) as month, SUBSTRING(e.entrancedate, 0, 12) as d, COUNT(e.id) AS hvisitorscount FROM AppBundle:qvHotEntrance e WHERE SUBSTRING(e.entrancedate, 1,4) = :year GROUP BY d order by d')->setParameter('year', $year);
                $data = $query->getResult();
                foreach ($data as $i) {
                $UTC = new \DateTimeZone("UTC");
                $newTZ = new \DateTimeZone("Asia/Almaty");
                $d = new \DateTime($i['d'], $UTC);
                $d->setTimezone( $newTZ );
                $d = $d->format('Y-m-d');
                $a = array($d, intval($i['hvisitorscount']));
                array_push($temp, $a);
                }
            }
            
            array_push($result, $flag);
            array_push($result, $temp);
            $serializer = $this->get('serializer');
            $checkpoints = $serializer->serialize($result, 'json');
            return new Response($checkpoints);
        }
    }

    
	 /**
     * @Route("/orders", name="show_orders")
     * @Method("GET")
     */
    public function showOrdersAction()
    {
    	$em = $this->getDoctrine()->getManager();
        $qvOrders = $em->getRepository('AppBundle:qvOrder')->findActiveOrdersForLeaser();
        return $this->render('AppBundle:Leaser:orders_list.html.twig', array(
        		'qvOrders'=>$qvOrders,
                'temp'=>1
        ));
    }

     /**
     * @Route("/allorders", name="show_orders_all")
     * @Method("GET")
     */
    public function showAllOrdersAction()
    {
        $em = $this->getDoctrine()->getManager();
        $qvOrders = $em->getRepository('AppBundle:qvOrder')->findAll();
        return $this->render('AppBundle:Leaser:orders_list.html.twig', array(
                'qvOrders'=>$qvOrders,
                'temp'=>2
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
                    'label'=>'Добавить', 
                'attr' =>array(
                    'class'=> 'btn btn-default',
                    'data-toggle'=> 'modal',
                    'data-target'=>'#myModal',
                    'onclick'=>"$('#myModal .modal-dialog').load('{{path('new_visitor')}}');"), ))
            ->add('lastnames', CollectionType::class, array(
                'entry_type' => TextType::class,
                'allow_add' => true,
                'prototype'=>true,
            ))
            ->add('firstnames', CollectionType::class, array(
                'entry_type' => TextType::class,
                'allow_add' => true,
                'prototype'=>true,
            ))
            ->add('patronimics', CollectionType::class, array(
                'entry_type' => TextType::class,
                'allow_add' => true,
                'prototype'=>true,
            ))
            ->add('birthdates', CollectionType::class, array(
                'entry_type' => BirthdayType::class,
                'allow_add' => true,
                'prototype'=>true,
                'entry_options'=>array(
                    'widget'=>'single_text',)
            ))
            ->add('genders', CollectionType::class, array(
                'entry_type' => TextType::class,
                'allow_add' => true,
                'prototype'=>true,
            ))
            ->add('passport_numbers', CollectionType::class, array(
                'entry_type' => NumberType::class,
                'allow_add' => true,
                'prototype'=>true,
            ))
            ->add('passport_issuedates', CollectionType::class, array(
                'entry_type' => DateTimeType::class,
                'allow_add' => true,
                'prototype'=>true,
                'entry_options'=>array(
                    'widget'=>'single_text',)
            ))
            ->add('passport_expiredates', CollectionType::class, array(
                'entry_type' => DateTimeType::class,
                'allow_add' => true,
                'prototype'=>true,
                'entry_options'=>array(
                    'widget'=>'single_text',)
            ))
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

            $lastnames = array();
            $firstnames = array();
            $patronimics = array();
            $birthdates = array();
            $genders = array();
            $passport_numbers = array();
            $passport_issuedates = array();
            $passport_expiredates = array();



            $j=0;
            foreach ($data['lastnames'] as $i) {
                $lastnames[$j] = $i;
                $j=$j+1;
            }
            $j=0;
            foreach ($data['firstnames'] as $i) {
                $firstnames[$j] = $i;
                $j=$j+1;
            }
            $j=0;
            foreach ($data['patronimics'] as $i) {
                $patronimics[$j] = $i;
                $j=$j+1;
            }
            $j=0;
            foreach ($data['birthdates'] as $i) {
                $birthdates[$j] = $i;
                $j=$j+1;
            }
            $j=0;
            foreach ($data['genders'] as $i) {
                $genders[$j] = $i;
                $j=$j+1;
            }

            $j=0;
            foreach ($data['passport_numbers'] as $i) {
                $passport_numbers[$j] = $i;
                $j=$j+1;
            }

            $j=0;
            foreach ($data['passport_issuedates'] as $i) {
                $passport_issuedates[$j] = $i;
                $j=$j+1;
            }

            $j=0;
            foreach ($data['passport_expiredates'] as $i) {
                $passport_expiredates[$j] = $i;
                $j=$j+1;
            }


            for($i = 0; $i<count($data['lastnames']); $i++)
            {
                $newVisitor = new qvVisitor();
                $visitorPassport = new qvVisitorDoc();

                $newVisitor->setLastname($lastnames[$i]);
                $newVisitor->setFirstname($firstnames[$i]);
                $newVisitor->setPatronimic($patronimics[$i]);
                $newVisitor->setBirthdate($birthdates[$i]);
                $qvGender = $em->getRepository('AppBundle:qvGender')->findOneBy(array('id'=>$genders[$i]));
                $newVisitor->setGender($qvGender);
                $visitorPassport->setNumber($passport_numbers[$i]);
                $visitorPassport->setIssuedate($passport_issuedates[$i]);
                $visitorPassport->setExpiredate($passport_expiredates[$i]);
                $visitorPassport->setFirstname($firstnames[$i]);
                $visitorPassport->setLastname($lastnames[$i]);
                $visitorPassport->setVisitor($newVisitor);
                $em->persist($newVisitor);
                $em->flush();
                $em->persist($visitorPassport);
                $em->flush();
                $qvOrder->addVisitors($newVisitor);

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
        $qvVisitors=$em->getRepository('AppBundle:qvVisitor')->findVisitorByUser($user);
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
                    'data-target'=>'#myModal'
                    ), ))
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
    * @Route("/leaser_info", name="leaser_info")
    * @Method("GET")
    */
    public function showLeaserInfoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $em1 = $this->getDoctrine()->getEntityManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userPassport=$em->getRepository('AppBundle:qvUserPassport')->findOneBy(array('user'=>$user->getId()));
        $qvContracts = $em->getRepository('AppBundle:qvContract')->findBy(array('leaser'=>$user->getLeaser()));
        $count1 = $em1->createQuery('SELECT count(contract) from AppBundle:qvContract contract where contract.leaser = :leaser')->setParameter('leaser',$user->getLeaser())->getSingleScalarResult();

        $visitors = $em->getRepository('AppBundle:qvVisitor')->findVisitorByUser($user->getId());
        $entrances = $em->getRepository('AppBundle:qvEntrance')->findBy(array('visitor'=>$visitors));
        $t = array();
        $j=0;
        foreach ($visitors as $v) {

        $count2 = $em1->createQuery('SELECT count(entrance) from AppBundle:qvEntrance entrance where entrance.visitor = :visitor')->setParameter('visitor',$v['id'])->getSingleScalarResult();
        $t[$j]['id'] = $v['id'];
        $t[$j]['entrance'] = $count2;

        $j = $j+1;

        }


        return $this->render('AppBundle:Leaser:leaser_info.html.twig', array(
            'userPassport'=>$userPassport,
            'user'=>$user,
            'qvContracts'=>$qvContracts,
            'countContract' => $count1,
            'visitors'=>$visitors,
            'countVisitor'=>count($visitors),
            'count'=>$t
        ));
    }


}

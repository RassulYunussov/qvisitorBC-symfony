<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use AppBundle\Entity\qvHotEntrance;
use AppBundle\Entity\qvEntrance;
use AppBundle\Entity\qvOrder;
use AppBundle\Entity\qvCheckpoint;
use AppBundle\Form\qvHotEntranceType;
use AppBundle\Entity\qvUser;
use AppBundle\Entity\qvVisitor;
use AppBundle\Repository\qvVisitorRepository;


/**
 * @Route("/checkpoint")
 */

class CheckpointController extends Controller
{
	



    /**
     *@Route("/index", name="select_checkpoint")
     *@Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qvBuildings=$em->getRepository('AppBundle:qvBuilding')->findAll();

        $em = $this->getDoctrine()->getManager();

        $data = array();

        $form = $this->createFormBuilder($data)
            ->add('building',  EntityType::class, array(
                'label'=>'Здание',
        'class' => 'AppBundle\Entity\qvBuilding')
            )
            ->add('checkpoint',  EntityType::class, array(
                'label'=>'КПП',
        'class' => 'AppBundle\Entity\qvCheckpoint')
            )
            ->getForm()
        ;
    
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            $em = $this->getDoctrine()->getManager();
            $session  = $this->get("session");
            $data = $form->getData();
            $session->set('checkpoint', $data['checkpoint']);
            $session->set('building', $data['building']);

             return $this->redirectToRoute('entrance', array());
            }
        
        return $this->render('AppBundle:Checkpoint:index.html.twig', array(
            'qvBuildings'=> $qvBuildings,
            'data' => $data,
            'form' => $form->createView(),
            ));
    }


    /**
     *@Route("/bybuilding", name="checkpoints")
     *@Method("GET")
     */

    public function indexAjaxAction(Request $request)
    {
        if($request->isXmlHttpRequest()) {
        	$buildingId = $request->get('id',1);
        	$em = $this->getDoctrine()->getManager();
        	$qvCheckpoints=$em->getRepository('AppBundle:qvCheckpoint')->findByBuildingId($buildingId);
        	$serializer = $this->get('serializer');
        	$checkpoints = $serializer->serialize($qvCheckpoints, 'json');
        	return new Response($checkpoints);
        }
    }




	/**
	 * @Route("/home", name="home")
	 * @Method("GET")
	 */
	public function homeAction()
	{
	
		return $this->render('AppBundle:Checkpoint:home.html.twig', array(
	
		));
	}
	
	
	
    /**
     * @Route("/entrance", name="entrance")
     * @Method("GET")
     */
    public function entranceAction()
    {

        $em = $this->getDoctrine()->getManager();
        $qvEntrances = $em->getRepository('AppBundle:qvEntrance')->findCurrentEntrance();
    
        return $this->render('AppBundle:Checkpoint:entrance.html.twig', array( 
                'qvEntrances'=>$qvEntrances,
               ));
    }
    
    /**
     * @Route("/entrance-registration", name="entrance_registration")
     * @Method({"GET", "POST"})
     */
    public function entranceRegPageLoadAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
        $qvOrders = $em->getRepository('AppBundle:qvOrder')->findAll();
    	return $this->render('AppBundle:Checkpoint:entrancereg.html.twig', array(
            'qvOrders'=>$qvOrders,
    	));
    }

    /**
     * @Route("/add_entrance/{qvOrder}/{qvVisitor}",name="add_entrance")
     * @ParamConverter("qvOrder", class="AppBundle:qvOrder")
     * @ParamConverter("qvVisitor", class="AppBundle:qvVisitor")
     * @Method("GET")
     */
    public function entranceRegAction(qvOrder $qvOrder, qvVisitor $qvVisitor)
    {
        $entrance = new qvEntrance();
        $entrance->setEntrancedate(new \DateTime());
        $entrance->setOrder($qvOrder);
        $entrance->setVisitor($qvVisitor);
        //entrance setCheckpoint, setUser using sessions
        $em = $this->getDoctrine()->getManager();
        $em->persist($entrance);
        $em->flush();
        return $this->redirectToRoute('entrance');
    }




    /**
     * @Route("/hotentrance", name="hotentranc")
     * @Method("GET")
     */
    public function hotentranceAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$qvHotEntrances = $em->getRepository('AppBundle:qvHotEntrance')->findCurrentHotEntrance();
    	
    	return $this->render('AppBundle:Checkpoint:hotentrance.html.twig', array(
    			'qvHotEntrances' => $qvHotEntrances,
    	));
    	
    }
    

    /**
     * @Route("/test/{qvOrder}", name="test")
     * @ParamConverter("qvOrder", class="AppBundle:qvOrder")
     * @Method({"GET", "POST"})
     */
    public function testAction(Request $request, $qvOrder)
    {   
            $em = $this->getDoctrine()->getManager();

        $data = array();
            $form = $this->createFormBuilder($data)
                ->add('visitors', EntityType::class, [
                'class'     => 'AppBundle:qvVisitor',
                'query_builder' => function (qvVisitorRepository $repo) use ($qvOrder) {
                    return $repo->createQueryBuilder('f')
                        ->innerJoin('f.orders', 'o')
                        ->where('o.id = :id')
                        ->setParameter('id', $qvOrder);
                },
                'multiple'  => true,
                'expanded'=> true
            ])
                ->getForm();

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

           $qvUser = new qvUser();
           $qvUser = $this->get('security.token_storage')->getToken()->getUser();
           $session = $this->get("session");
           $chp = $session->get('checkpoint');
           $checkpoint = $em->getRepository('AppBundle:qvCheckpoint')->findOneBy(array('id'=>$chp->getId()));
                foreach ($data['visitors'] as $visitor) {
            $entrance = new qvEntrance();
            $entrance->setEntrancedate(new \DateTime());
            $entrance->setOrder($qvOrder);
            $entrance->setVisitor($visitor);
            $entrance->setCheckpoint($checkpoint);
            $entrance->setUser($qvUser);
            $em = $this->getDoctrine()->getManager();
            $em->persist($entrance);
            $em->flush();
                }

           

             return $this->redirectToRoute('entrance', array());
            }
                return $this->render('AppBundle:Leaser:test.html.twig', array(
            'form' => $form->createView(),
            ));
    }


    /**
     * @Route("/entrance-registration/{qvOrder}", name="select-visitor")
     * @ParamConverter("qvOrder", class="AppBundle:qvOrder")
     * @Method({"GET", "POST"})
     */
    public function selectVisitorAction(Request $request, $qvOrder)
    {
          $data = array();
            $em = $this->getDoctrine()->getManager();
            $form = $this->createFormBuilder($data)
                ->add('visitors', EntityType::class, [
                'class'     => 'AppBundle:qvVisitor',
                'query_builder' => function (qvVisitorRepository $repo) use ($qvOrder) {
                    return $repo->createQueryBuilder('f')
                        ->innerJoin('f.orders', 'o')
                        ->where('o.id = :id')
                        ->setParameter('id', $qvOrder);
                },
                'multiple'  => true,
                'expanded'=> true
            ])
                ->getForm();
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData(); $qvUser = new qvUser();
            $qvUser = $this->get('security.token_storage')->getToken()->getUser();
            $session = $this->get("session");
            $chp = $session->get('checkpoint');
            $checkpoint = $em->getRepository('AppBundle:qvCheckpoint')->findOneBy(array('id'=>$chp->getId()));
                foreach ($data['visitors'] as $visitor) {
                    $entrance = new qvEntrance();
                    $entrance->setEntrancedate(new \DateTime());
                    $entrance->setOrder($qvOrder);
                    $entrance->setVisitor($visitor);
                    $entrance->setCheckpoint($checkpoint);
                    $entrance->setUser($qvUser);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($entrance);
                    $em->flush();
                        }
             return $this->redirectToRoute('entrance');
            }
                    if($request->isXmlHttpRequest()) {
            
            $qvVisitors = $em->getRepository('AppBundle:qvVisitor')->findVisitorByOrder($qvOrder);


            return $this->render('AppBundle:Checkpoint:selectvisitor.html.twig', array(
                    'qvVisitors' => $qvVisitors,

                    'form' => $form->createView(),
            ));
        }
    }


    /**
     * @Route("/hotentrance/{id}", name="hotentrance")
     * @Method("GET")
     */
    public function hotentranceDetailsAction(Request $request,$id)
    {
    	if($request->isXmlHttpRequest()) {
    		$em = $this->getDoctrine()->getManager();
    		 
    		$qvHotEntrance = $em->getRepository('AppBundle:qvHotEntrance')->findOneBy(array('id'=>$id));
    		
    		return $this->render('AppBundle:Checkpoint:hotentrancedetails.html.twig', array(
    				'qvHotEntrance' => $qvHotEntrance,
    		));
    	}
    }
    
    


    
    /**
     * @Route("/hot-entrance-registration" , name="hereg")
     * @Method({"GET", "POST"})
     */
    public function hotEnranceRegAction(Request $request)
    {
    	$qvHotEntrance = new qvHotEntrance();
        $em = $this->getDoctrine()->getManager();
        $data = array();

        $form = $this->createFormBuilder($data)

            ->add('lastname', TextType::class, array(
                'label'=>'Фамилия',))
            ->add('firstname', TextType::class, array(
                'label'=>'Имя',))
            ->add('patronimic', TextType::class, array(
                'label'=>'Отчество',))
            ->add('documentnumber', NumberType::class, array(
                'label'=>'Номер документа',))
            ->add('organization', TextType::class, array(
                'label'=>'Организация',))
            ->add('attendant', TextType::class, array(
                'label'=>'Встречающий',))
            ->add('comment', TextareaType::class, array(
                'label'=>'Дополнительно',))
            ->add('entrancedate', DateTimeType::class, array(
                'data'=> new \DateTime(),
                'label'=>'Время посещения',
                'widget' => 'single_text', 
                'format' =>'dd/MM/yyyy hh:mm',
                'html5' => false,
                'model_timezone'=>'Asia/Almaty',
                'attr' => array(
                    'class' => 'form-control'),
                'disabled' => 'true',
                'placeholder' => array('datetime' => 'Datetime',),
                ))
            ->add('leaser', EntityType::class, array(
                'class' => 'AppBundle\Entity\qvLeaser',
                'label' => 'Арендатор'))
            ->getForm();


        $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
           
           $em = $this->getDoctrine()->getManager();
           $em2 = $this->getDoctrine()->getEntityManager();
           $data = $form->getData();

           $qvUser = new qvUser();
           $qvUser = $this->get('security.token_storage')->getToken()->getUser();
           $session = $this->get("session");
           $chp = $session->get('checkpoint');
           $checkpoint = $em->getRepository('AppBundle:qvCheckpoint')->findOneBy(array('id'=>$chp->getId()));

           $qvHotEntrance->setFirstname($data['firstname']);
           $qvHotEntrance->setLastname($data['lastname']);
           $qvHotEntrance->setPatronimic($data['patronimic']);
           $qvHotEntrance->setDocumentnumber($data['documentnumber']);
           $qvHotEntrance->setOrganization($data['organization']);
           $qvHotEntrance->setAttendant($data['attendant']);
           $qvHotEntrance->setComment($data['comment']);
           $qvHotEntrance->setEntrancedate(new \DateTime());
           $qvHotEntrance->setCheckpoint($checkpoint);
           $qvHotEntrance->setLeaser($data['leaser']);
           $qvHotEntrance->setUser($qvUser);
        
            $em->persist($qvHotEntrance);
            $em->flush();

    return $this->redirectToRoute('hotentranc', array());
}

        return $this->render('AppBundle:Checkpoint:hotentrancereg.html.twig', array(
            
            'data' => $data,
            'form' => $form->createView(),
        ));   

    }


    /**
    * @Route("/entrance/visitor/{id}", name="visitor_info")
    * @Method("GET")
    */
    public function visitorInfoAction(Request $request, $id)
    {
        $em=$this->getDoctrine()->getManager();
        $entrance = $em->getRepository('AppBundle:qvEntrance')->findOneBy(array('id'=>$id));
        $qvUserpassport = array();
        $qvVisitor = $entrance->getVisitor();
        $qvEntrances = $em->getRepository('AppBundle:qvEntrance')->findEntrancesByVisitor($qvVisitor);
        foreach ($qvEntrances as $qvEntrance) {
            $qvUserPassport = $em->getRepository('AppBundle:qvUserPassport')->findUserpassportByEntrance($qvEntrance);

        }
        return $this->render('AppBundle:Checkpoint:visitor_info.html.twig', array(
            'qvVisitor'=>$qvVisitor,
            'qvEntrances'=>$qvEntrances,
            'qvUserPassport'=>$qvUserPassport));
    }
    
   
}

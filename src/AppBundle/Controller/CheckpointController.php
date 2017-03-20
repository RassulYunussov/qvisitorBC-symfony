<?php

namespace AppBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ParameterBag;
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
 * @Security("has_role('ROLE_CHECKPOINT')")
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
        $qvVisitors = array();
        $em = $this->getDoctrine()->getManager();
        $qvEntrances = $em->getRepository('AppBundle:qvEntrance')->findCurrentEntrance();
        return $this->render('AppBundle:Checkpoint:entrance/entrance.html.twig', array( 
                'qvEntrances'=>$qvEntrances
               ));
    }
    

     /**
     * @Route("/entrance/{id}", name="entrance-details")
     * @Method("GET")
     */
    public function entranceDetailsAction(Request $request,$id)
    {
        if($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            
            $qvEntrance = $em->getRepository('AppBundle:qvEntrance')->findOneBy(array('id'=>$id));
            
            $visitorDoc = $em->getRepository('AppBundle:qvVisitorDoc')->findOneBy(array('id'=>$qvEntrance->getVisitor()->getId()));
            return $this->render('AppBundle:Checkpoint:entrance/entrance_info.html.twig', array(
                    'qvEntrance' => $qvEntrance,
                    'visitorDoc' => $visitorDoc
            ));
        }
    }


    /**
     * @Route("/entrance-registration", name="entrance_registration")
     * @Method({"GET", "POST"})
     */
    public function entranceRegPageLoadAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
        $qvOrders = $em->getRepository('AppBundle:qvOrder')->findActiveOrders();
    	return $this->render('AppBundle:Checkpoint:entrance/entrancereg.html.twig', array(
            'qvOrders'=>$qvOrders,
    	));
    }



    /**
     * @Route("/hotentrance", name="hotentranc")
     * @Method("GET")
     */
    public function hotentranceAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$qvHotEntrances = $em->getRepository('AppBundle:qvHotEntrance')->findCurrentHotEntrance();
    	
    	return $this->render('AppBundle:Checkpoint:hotentrance/hotentrance.html.twig', array(
    			'qvHotEntrances' => $qvHotEntrances,
    	));
    	
    }
    


    /**
     * @Route("/entrance-registr/{qvOrder}", name="select-vis")
     * @ParamConverter("qvOrder", class="AppBundle:qvOrder")
     * @Method({"GET", "POST"})
     */
    public function selectVisitorModalAction(Request $request, $qvOrder)
    {
    if($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            
            $qvVisitors = $em->getRepository('AppBundle:qvVisitor')->findVisitorByOrder($qvOrder);
            return $this->render('AppBundle:Checkpoint:entrance/selectvisitor.html.twig', array(
                    'qvVisitors' => $qvVisitors,
                    'qvOrder'=>$qvOrder,
                    
            ));
        }
    }


    /**
     * @Route("/entrance-registration/{qvOrder}", name="select-visitor")
     * @ParamConverter("qvOrder", class="AppBundle:qvOrder")
     * @Method({"GET", "POST"})
     */
    public function selectVisitorAction(Request $request, qvOrder $qvOrder)
    {
        $data = array();
        $yy = $request->query->all();
        $u = $request->get('form');
        $r = $u['visitors'];

            $data3 = $request->query->get('test');
            $tt = $request->get('form[visitors][]', null, true);
          
             $em = $this->getDoctrine()->getManager();
              $qvUser = new qvUser();
            $qvUser = $this->get('security.token_storage')->getToken()->getUser();
            $session = $this->get("session");
            $chp = $session->get('checkpoint');
            $checkpoint = $em->getRepository('AppBundle:qvCheckpoint')->findOneBy(array('id'=>$chp->getId()));
            foreach ($r as $visitor) {
                    $qvVisitor = $em->getRepository('AppBundle:qvVisitor')->findOneBy(array('id'=>$visitor));
                    $entrance = new qvEntrance();
                    $entrance->setEntrancedate(new \DateTime());
                    $entrance->setOrder($qvOrder);
                    $entrance->setVisitor($qvVisitor);
                    $entrance->setCheckpoint($checkpoint);
                    $entrance->setUser($qvUser);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($entrance);
                    $em->flush();
                        }
            return $this->redirectToRoute('entrance');
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
    		
    		return $this->render('AppBundle:Checkpoint:hotentrance/hotentrancedetails.html.twig', array(
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

        return $this->render('AppBundle:Checkpoint:hotentrance/hotentrancereg.html.twig', array(
            
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
        $qvUserpassport = array();
        $qvVisitor = $em->getRepository('AppBundle:qvVisitor')->findOneBy(array('id'=>$id));
        $qvEntrances = $em->getRepository('AppBundle:qvEntrance')->findEntrancesByVisitor($qvVisitor);
        if (count($qvEntrances) == 0) {
            $message = "У данного человека нет посещений";
             return $this->render('AppBundle:Checkpoint:visitor/visitor_info_error.html.twig', array(
            'qvVisitor'=>$qvVisitor,
            'message'=>$message));
        }
         else {

        foreach ($qvEntrances as $qvEntrance) {
            $qvUserPassport = $em->getRepository('AppBundle:qvUserPassport')->findUserpassportByEntrance($qvEntrance);

        }
        return $this->render('AppBundle:Checkpoint:visitor/visitor_info.html.twig', array(
            'qvVisitor'=>$qvVisitor,
            'qvEntrances'=>$qvEntrances,
            'qvUserPassport'=>$qvUserPassport));
        }

    }
    
    /**
    * @Route("/orders", name="orders_list")
    * @Method({"GET", "POST"})
    */
    public function ordersListAction()
    {
        $em = $this->getDoctrine()->getManager();
        $qvUserPassport = array();
        $qvOrders = $em->getRepository('AppBundle:qvOrder')->findActiveOrders();
        foreach ($qvOrders as $qvOrder) 
        {
            $qvUserPassport = $em->getRepository('AppBundle:qvUserPassport')->findUserpassportByOrder($qvOrder);
        }
        
        return $this->render('AppBundle:Checkpoint:visitor/orders_list.html.twig', array(
            'qvOrders' => $qvOrders,
            'qvUserPassport' => $qvUserPassport
            ));
    }

}

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
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use AppBundle\Entity\qvHotEntrance;
use AppBundle\Entity\qvEntrance;
use AppBundle\Entity\qvOrder;
use AppBundle\Entity\qvCheckpoint;
use AppBundle\Form\qvHotEntranceType;
use AppBundle\Entity\qvUser;
use AppBundle\Entity\qvVisitor;


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
        $qvEntrances = $em->getRepository('AppBundle:qvEntrance')->findAll();
    
        return $this->render('AppBundle:Checkpoint:entrance.html.twig', array( 
                'qvEntrances'=>$qvEntrances,
               ));
    }
    
    /**
     * @Route("/entrance-registration", name="entrance_registration")
     * @Method("GET")
     */
    public function entranceRegPageLoadAction()
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
     *@Route("/set-registered", name="set_registered")
     *@Method("GET")
     */

    public function setRegisteredAjaxAction(Request $request)
    {
        if($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $leaserId=$request->get('id', 1);
            $qvLeaser = $em->getRepository('AppBundle:qvLeaser')->findOneBy(array('id'=>$leaserId));
            $serializer = $this->get('serializer');
            $leaser = $serializer->serialize($qvLeaser, 'json');
            return new Response($leaser);
        }
    }



    /**
     * @Route("/hotentrance", name="hotentranc")
     * @Method("GET")
     */
    public function hotentranceAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$qvHotEntrances = $em->getRepository('AppBundle:qvHotEntrance')->findAll();
    	
    	return $this->render('AppBundle:Checkpoint:hotentrance.html.twig', array(
    			'qvHotEntrances' => $qvHotEntrances,
    	));
    	
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
    
   
}

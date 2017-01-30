<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\qvHotEntrance;
use AppBundle\Entity\qvEntrance;
use AppBundle\Entity\qvOrder;
use AppBundle\Form\qvHotEntranceType;
use AppBundle\Entity\qvVisitor;


/**
 * @Route("/checkpoint")
 */

class CheckpointController extends Controller
{
	



    /**
     *@Route("/index")
     *@Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $qvBuildings=$em->getRepository('AppBundle:qvBuilding')->findAll();
       
        
        return $this->render('AppBundle:Checkpoint:index.html.twig', array(
                'qvBuildings'=> $qvBuildings
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
     *@Route("/add-leaser", name="add-leaser")
     *@Method("GET")
     */

    public function addLeaserAjaxAction(Request $request)
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
	 * @Route("/home")
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
        return $this->redirectToRoute('entrance_registration');
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
        $form = $this->createForm('AppBundle\Form\qvHotEntranceType', $qvHotEntrance);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $qvLeasers = $em->getRepository('AppBundle:qvLeaser')->findAll();
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvHotEntrance);
            $em->flush();

            return $this->redirectToRoute('hotentranc', array('id' => $qvHotEntrance->getId()));
        }

    	return $this->render('AppBundle:Checkpoint:hotentrancereg.html.twig', array(
            'qvHotEntrance' => $qvHotEntrance,
            'qvLeasers'=>$qvLeasers,
            'form' => $form->createView()
    	));
    }
    
   
}

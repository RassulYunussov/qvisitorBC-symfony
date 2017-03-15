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
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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
 * @Route("/adminbc")
 * @Security("has_role('ROLE_ADMIN')")
 */
class AdminBCController extends Controller
{
    /**
     * @Route("/index", name = "main_page")
     * @Method("GET")
     */
    public function adminbcAction()
    {    $em = $this->getDoctrine()->getManager();
    
    return $this->render('AppBundle:AdminBC:index.html.twig', array(
   ));
    }
 
    /**
     * Displays a form to edit an existing qvLeaser entity.
     * @ParamConverter("qvUser", class="AppBundle:qvUser")
     * @Route("/user/{qvUser}/disabled", name="user_disabled")
     * @Method({"GET", "POST"})
     */
    public function DisabledUserAction(Request $request, qvUser $qvUser)
    {
        $em = $this->getDoctrine()->getManager();
        
        $qvUser = $em->getRepository('AppBundle:qvUser')->find($qvUser);
        $role = $qvUser->getRole();
        $code = $role->getCode();

        $qvUser->setDisabled(1);
        $em->merge($qvUser);
        $em->flush();
          if($code == 'ROLE_LEASER')
            return $this->redirectToRoute('leasers_list');
        else if ($code == 'ROLE_CHECKPOINT')
            return $this->redirectToRoute('security_list');
    }   

      /**
     * Displays a form to edit an existing qvLeaser entity.
     * @ParamConverter("qvUser", class="AppBundle:qvUser")
     * @Route("/user/{qvUser}/enabled", name="user_enabled")
     * @Method({"GET", "POST"})
     */
    public function EnabledUserAction(Request $request, qvUser $qvUser)
    {
        $em = $this->getDoctrine()->getManager();
        
        $qvUser = $em->getRepository('AppBundle:qvUser')->find($qvUser);
        $role = $qvUser->getRole();
        $code = $role->getCode();

        $qvUser->setDisabled(0);
        $em->merge($qvUser);
        $em->flush();
        

        if($code == 'ROLE_LEASER')
            return $this->redirectToRoute('leasers_list');
        else if ($code == 'ROLE_CHECKPOINT')
            return $this->redirectToRoute('security_list');
    }   
    
     /**
     * @Route("/contracts", name="contracts_list")
     * @Method("GET")
     */
    public function index_contractsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $qvContracts = $em->getRepository('AppBundle:qvContract')->findAll();
        return $this->render('AppBundle:Adminbc:leasers_control/contracts/contracts_list.html.twig', array(
            'qvContracts' => $qvContracts,
        ));
    }   
        
         /**
     * @Route("/leaser/{qvLeaser}/contracts/create_contract", name="contracts_create")
     * @ParamConverter("qvLeaser", class="AppBundle:qvLeaser")
     * @Method({"GET", "POST"})
     */
    public function contractCreateAction(Request $request, qvLeaser $qvLeaser)
    {
        $em = $this->getDoctrine()->getManager();
            
        $qvContract = new qvContract();
        $contr = new qvContract();
        $data = array();
        $sectors_array = array();
        if($request->isXmlHttpRequest()) {
        $sectors_array = $_POST['form_sectors'];
    }

        $sectorname = new qvSector();
        $qvSector = new qvSector();
        $form = $this->createFormBuilder($data)
        ->add('name', TextType::class, array(
            'label'=>'Номер контракта',
            'attr'=>array('class'=>'form-control form-input')))
         ->add('startdate', DateType::class, array(
    'widget' => 'single_text',
    'attr' => ['class' => 'js-datepicker'],
    'attr' => array(
                'class' => 'type_date-inline form-margin'),
))
  
           ->add('enddate', DateType::class, array(
    'widget' => 'single_text',
    'attr' => ['class' => 'js-datepicker'],
    'attr' => array(
    'class' => 'type_date-inline form-margin')
    ))
         ->add('sectors', EntityType::class, array(
            'class'=>'AppBundle:qvSector',
            'multiple'=>true, 
            'attr'=> array('class' => 'form-margin')
    ))
        ->getForm()
            ;
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $qvContract->setName($data['name']);
            $qvContract->setStartdate($data['startdate']);
            $qvContract->setEnddate($data['enddate']);
            $qvContract->setLeaser($qvLeaser);
            foreach ($data['sectors'] as $v) {
            $qvContract->addSectors($v);
            }   
            $em->persist($qvContract);
            $em->flush();
        
            return $this->redirectToRoute('leasers_show', array('id'=>$qvLeaser->getId()));
        }
        return $this->render('AppBundle:AdminBC:leasers_control/contracts/create_contract.html.twig', array(
            'qvContract' => $qvContract,
            'qvLeaser'=> $qvLeaser,
            'form' => $form->createView(),
        ));
    }

     /**
     *@Route("/bybuildings", name="floors")
     *@Method("GET")
     */

    public function indexFloorsAjaxAction(Request $request)
    {
        if($request->isXmlHttpRequest()) {
            $buildingId = $request->get('id',1);
            $em = $this->getDoctrine()->getManager();
            $qvfloors=$em->getRepository('AppBundle:qvFloor')->findByBuildingId($buildingId);
            $serializer = $this->get('serializer');
            $floors = $serializer->serialize($qvfloors, 'json');
            return new Response($floors);
        }
    }

    /**
     *@Route("/byfloors", name="sectors")
     *@Method("GET")
     */

    public function indexSectorsAjaxAction(Request $request)
    {
        if($request->isXmlHttpRequest()) {
            $floorId = $request->get('id',2);
            $em = $this->getDoctrine()->getManager();
            $qvsectors=$em->getRepository('AppBundle:qvSector')->findByFloorId($floorId);
            $serializer = $this->get('serializer');
            $sectors = $serializer->serialize($qvsectors, 'json');
            return new Response($sectors);
        }
    }

    /**
     *@Route("/bymybuildings", name="buildings")
     *@Method("GET")
     */
     public function indexBuildingsAjaxAction(Request $request)
    {
        if($request->isXmlHttpRequest()) {
            //$buildAll = $request->get();
            $em = $this->getDoctrine()->getManager();
            $qvbuildings=$em->getRepository('AppBundle:qvBuilding')->findAll();
            $serializer = $this->get('serializer');
            $buildings = $serializer->serialize($qvbuildings, 'json');
            return new Response($buildings);
        }
    }

     /**
     * Finds and displays a qvContract entity.
     * @ParamConverter("qvLeaser", class="AppBundle:qvLeaser")
     * @Route("/leaser/{qvLeaser}/contract/{id}/show", name="contracts_show")
     * @Method("GET")
     */
    public function showContractAction(qvContract $qvContract, qvLeaser $qvLeaser)
    {
        $em = $this->getDoctrine()->getManager();
        $deleteForm = $this->createDeleteContractForm($qvContract);
        
        $qvContracts = $em->getRepository('AppBundle:qvContract')->findOneByLeaser($qvLeaser);

        $onecontr = $em->getRepository('AppBundle:qvContract')->findOneById($qvContract);

        return $this->render('AppBundle:AdminBC:leasers_control/contracts/show_contract.html.twig', array(
            'qvContract' => $qvContract,
            'qvContracts' => $qvContracts,
            'qvLeaser'=> $qvLeaser,
            'onecontr' => $onecontr,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @Route("/showModal/{id}", name="contracts_modal")
     * @Method("GET")
     */
    public function ContractsDetailsAction(Request $request,$id)
    {
        if($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
             
            $qvModalContract = $em->getRepository('AppBundle:qvContract')->findOneBy(array('id'=>$id));
            
            return $this->render('AppBundle:AdminBC:leasers_control/contracts/detailscontract.html.twig', array(
                    'qvModalContract' => $qvModalContract,
            ));
        }
    }

    /**
     * Displays a form to edit an existing qvContract entity.
     * @ParamConverter("qvLeaser", class="AppBundle:qvLeaser")
     * @Route("/leaser/{qvLeaser}/contract/{id}/edit", name="contracts_edit")
     * @Method({"GET", "POST"})
     */
    public function editContractAction(Request $request, qvContract $qvContract, qvLeaser $qvLeaser)
    {
        $deleteForm = $this->createDeleteContractForm($qvContract);
         $em = $this->getDoctrine()->getManager();
            
        $qvContr = $em->getRepository('AppBundle:qvContract')->find($qvContract);

        $data = array();

        $editForm = $this->createFormBuilder($qvContr)
        ->add('name', TextType::class, array(
            'label'=>'Номер контракта',
            'attr'=>array('class'=>'form-control form-input')))
        ->add('startdate', DateType::class, array(
                'label'=>'Дата начала',
                'widget' => 'single_text', 
                'format' =>'dd/MM/yyyy',
                'html5' => false,
                'attr' => ['class' => 'js-datepicker'],
                'attr' => array(
                    'class' => 'form-margin type_date-inline'),
                'placeholder' => 'Укажите дату в формате дд/мм/гггг',
                ))
         ->add('enddate', DateType::class, array(
                'label'=>'Дата окончания',
                'widget' => 'single_text', 
                'format' =>'dd/MM/yyyy',
                'html5' => false,
                'attr' => array(
                    'class' => 'form-control type_date-inline'),
                'placeholder' => 'Укажите дату в формате дд/мм/гггг',
                ))
          ->add('sectors', EntityType::class, array(
                'class' => 'AppBundle\Entity\qvSector',
                'attr' => array(
                    'class' => 'form-control form-input'),
                'label'=>'Сектора',
                'multiple' =>'true',
                ))
        ->getForm()
            ;
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
          
            $em->flush();
            return $this->redirectToRoute('leasers_show', array('id'=>$qvLeaser->getId()));
        }
        
        return $this->render('AppBundle:AdminBC:leasers_control/contracts/edit_contract.html.twig', array(
            'qvContract' => $qvContract,
            'qvLeaser'=> $qvLeaser,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * Deletes a qvOrder entity.
     * @ParamConverter("qvLeaser", class="AppBundle:qvLeaser")
     * @Route("/leaser/{qvLeaser}/contract/{id}/delete", name="contract_deleting")
     * @Method({"GET", "POST"})
     */

    public function deleteAction(Request $request, qvContract $qvContract, qvLeaser $qvLeaser)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($qvContract);
        $em->flush();
        return $this->redirectToRoute('leasers_show', array('id'=>$qvLeaser->getId()));
    }

    /**
     * Deletes a qvContract entity.
     * @Route("/contract/{id}/delete", name="contracts_delete")
     * @Method("DELETE")
     */
    public function deletecontractAction(Request $request, qvContract $qvContract)
    {
        $form = $this->createDeleteContractForm($qvContract);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($qvContract);
            $em->flush();
        }
        return $this->redirectToRoute('leasers_list');
    }

    /**
     * Creates a form to delete a qvContract entity.
     *
     * @param qvContract $qvContract The qvContract entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteContractForm(qvContract $qvContract)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('contracts_delete', array('id' => $qvContract->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

      /**
     * @Route("/analytics/visitors", name = "attendece-visitors")
     * @Method("GET")
     */
    public function AnalyticsAttendanceVisitorsAction()
    { 
     return $this->render('AppBundle:AdminBC:Analytics/visitorsByLeasers.html.twig', array());
    }


    /**
     *@Route("/allcheckpoints", name="Allcheckpoints")
     *@Method("GET")
     */

    public function indexAjaxCheckpointsAction(Request $request)
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
     * @Route("/analytics/visitorsbyorders", name = "visitorsbyorders")
     * @Method("GET")
     */
    public function AnalyticsVisitorsByOrdersAction()
    {
        $checkpoints = $this->getDoctrine()->getManager()->getRepository('AppBundle:qvCheckpoint')->findAll();

        return $this->render('AppBundle:AdminBC:Analytics/visitorsByOrders.html.twig', array(
        'checkpoints'=>$checkpoints,
    ));           
    }
  
       /**
     * @Route("/analytics/dependencebyVisitors", name = "dependencebyVisitors")
     * @Method("GET")
     */
    public function AnalyticsDependenceVisitorsAction()
    {
        return $this->render('AppBundle:AdminBC:Analytics/visitorsAndSectors.html.twig', array(
        ));
    }
  
 /**
     * @Route("/analytics/attendance", name ="attendance")
     * @Method("GET")
     */
public function chartAction()
{
    return $this->render('AppBundle:AdminBC:Analytics/attendance.html.twig', array());
}

    /**
     *@Route("/byattendance", name="attendance_day")
     *@Method({"GET", "POST"})
     */
    public function indexAttendanceAjaxAction(Request $request)
    {
        if($request->isXmlHttpRequest()) {
        $bd = $_POST['dateBegin'];
        $ed = $_POST['dateEnd'];
        $data = array();
        $result = array();
        $cat = array();
        $em = $this->getDoctrine()->getEntityManager();
        $query=$em->createQuery('SELECT count(e) AS rank, SUBSTRING(e.entrancedate, 0, 12) as day, COUNT(e.visitor) AS visitorscount FROM AppBundle:qvEntrance e WHERE day >=  :firstdate and day <= :seconddate GROUP BY day order by day')->setParameters(array('firstdate'=>$bd, 'seconddate'=>$ed));
        $data = $query->getResult();
          foreach ($data as $i) {
            $UTC = new \DateTimeZone("UTC");
            $newTZ = new \DateTimeZone("Asia/Almaty");
            $d = new \DateTime($i['day'], $UTC);
            $d->setTimezone( $newTZ );
            $d = $d->format('Y-m-d');
            $a = array($d, intval($i['visitorscount']));
            array_push($result, $a);
            }
        $serializer = $this->get('serializer');
        $res = $serializer->serialize($result, 'json');
        return new Response($res);
        }
    }
    /**
     *@Route("/byleasers", name="leasers-attendance")
     *@Method({"GET", "POST"})
     */
    public function indexLeasersAttendanceAjaxAction(Request $request)
    {
        if($request->isXmlHttpRequest()) {
        $bd = $_POST['dateBegin'];
        $ed = $_POST['dateEnd'];
        $data = array();
        $result = array();
        $em = $this->getDoctrine()->getEntityManager();

    $query = $em->createQuery('SELECT l.name AS rank, SUBSTRING(e.entrancedate, 0, 12) as month, COUNT(e.visitor) AS visitorscount FROM AppBundle:qvEntrance e JOIN e.user u JOIN u.leaser l WHERE month >= :firstdate and month <= :seconddate GROUP BY l')->setParameters(array('firstdate'=> $bd, 'seconddate'=>$ed));
        $data = $query->getResult();
          
        foreach ($data as $i) {
            $a = array($i['rank'], intval($i['visitorscount']));
            array_push($result, $a);
        }
        $serializer = $this->get('serializer');
        $res = $serializer->serialize($result, 'json');
        return new Response($res);
        }
    }

    /**
     *@Route("/byattendanceofOrders", name="attendance_by_orders")
     *@Method({"GET", "POST"})
     */
    public function indexAttendanceByOrdersAjaxAction(Request $request)
    {
        if($request->isXmlHttpRequest()) {
        $checkpoint = $request->get('checkpoint',1);
        $dataEntrance = array();
        $dataHotEntrance = array();
        $qvEntrance = array();
        $HotEntrance = array();
        $result = array();
        $em = $this->getDoctrine()->getEntityManager();
       
        $queryEntrance=$em->createQuery('SELECT count(e) AS rank, SUBSTRING(e.entrancedate, 6, 2) as month, COUNT(e.visitor) AS visitorscount FROM AppBundle:qvEntrance e WHERE e.checkpoint = :name GROUP BY month order by month')->setParameter('name', $checkpoint);
        $dataEntrance = $queryEntrance->getResult();
        
        $queryHotEntrance = $em->createQuery('SELECT count(he) AS rank, SUBSTRING(he.entrancedate, 6, 2) as month, COUNT(he.id) AS hvisitorscount FROM AppBundle:qvHotEntrance he WHERE he.checkpoint = :name GROUP BY month order by month')->setParameter('name', $checkpoint);
        $dataHotEntrance = $queryHotEntrance->getResult();    

        foreach ($dataHotEntrance as $i) {
            $a = array($i['month'], intval($i['hvisitorscount']));
            array_push($HotEntrance, $a);
            }
        foreach ($dataEntrance as $i) {
            $a = array($i['month'], intval($i['visitorscount']));
            array_push($qvEntrance, $a);
            }
        array_push($result,$HotEntrance);
        array_push($result,$qvEntrance);

        $serializer = $this->get('serializer');

        $resultEntrance = $serializer->serialize($result,'json');
        //$resultHotEntrance = $serializer->serialize($HotEntrance, 'json');
        
        return new Response($resultEntrance);
        }
    }
}
    
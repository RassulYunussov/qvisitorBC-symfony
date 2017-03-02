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
use Ob\HighchartsBundle\Highcharts\Highchart;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Entity\qvLeaser;
use AppBundle\Form\qvNewLeaserType;
use AppBundle\Entity\qvContract;
use AppBundle\Form\qvContractType;
use AppBundle\Entity\qvFloor;
use AppBundle\Entity\qvFloorType;
use AppBundle\Entity\qvSector;
use AppBundle\Entity\qvSectorType;
use AppBundle\Entity\qvCheckpoint;
use AppBundle\Entity\qvCheckpointType;
use AppBundle\Entity\qvBuilding;
use AppBundle\Form\qvBuildingType;
use AppBundle\Entity\qvUserPassport;
use AppBundle\Form\qvUserPassportType;
use AppBundle\Entity\qvUser;
use AppBundle\Form\qvUserType;
use AppBundle\Entity\qvRole;
use AppBundle\Form\UserAccountType;

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
    
    $currentDate = new \Datetime("UTC");

    $monthdate = date("Y-m-d", mktime(0, 0, 0, date('m'), date('d') - 30, date('Y')));
    $month = '30';
    /*$maxVisitors = $this->getDoctrine()->getEntityManager()->
    createQuery('SELECT COUNT(e.visitor) FROM AppBundle:qvEntrance e WHERE e.entrancedate <  :currentdate or e.entrancedate =  :currentdate and e.entrancedate > :monthdate or e.entrancedate = :monthdate')->setParameters(array('currentdate'=> $currentDate, 'monthdate'=>($monthdate)))->getSingleScalarResult();
    */      
    $result = array();

    $emm = $this->getDoctrine()->getEntityManager();
            $query = $emm->createQuery('SELECT count(e) AS rank, SUBSTRING(e.entrancedate, 0, 12) as month, COUNT(e.visitor) AS visitorscount FROM AppBundle:qvEntrance e WHERE month <=  :currentdate and month >= :monthdate GROUP BY month order by month')->setParameters(array('currentdate'=> $currentDate->format('Y-m-d'), 'monthdate'=>$monthdate));
            $data = $query->getResult();

            $query2 = $emm->createQuery('SELECT e.id as num, e.entrancedate as day FROM AppBundle:qvEntrance e WHERE e.entrancedate <=  :currentdate group by e.entrancedate')->setParameter('currentdate', $currentDate);
            $dates = $query2->getResult();
         //   var_dump($data);    
                
    // Chart
        foreach ($data as $i) {
            $a = array($i['rank'], intval($i['visitorscount']));
            array_push($result, $a);
            }
     $series = array(
        array("name" => "Количество посетителей", "data" => $result)
    );

    $ob = new Highchart();
    $ob->chart->renderTo('attendanceBC');  // The #id of the div where to render the chart
    //$ob->chart->type('spline');
    $ob->title->text('График посещаемости БЦ');
    $ob->xAxis->type('date');
    $ob->xAxis->title(array('text'  => "Период времени"));
    $ob->xAxis->data(array(array("data"=>$dates)));
    $ob->xAxis->dateTimeLabelFormats(array('month'=> '%e. %b', 'year'=> '%b'));

    $ob->yAxis->title(array('text'  => "Количество посетителей"));
    $ob->series($series);

    return $this->render('AppBundle:AdminBC:index.html.twig', array(
        'chart' => $ob,
        'data' => $result,
        'currentDate'=>$currentDate,
        'monthdate'=>$monthdate,
    ));
    }
          
     /**
     * @Route("/leasers", name="leasers_list")
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
     * @Route("/leasers/create", name="leasers_create")
     * @Method({"GET", "POST"})
     */
    public function createLeaserAction(Request $request)
    {
       $qvLeaser = new qvLeaser();
       $qvUser = new qvUser();
        $qvUserPassport = new qvUserPassport();
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
            'form' => $form->createView()
        ));
    }
    /**
     * Finds and displays a qvLeaser entity.
     *
     * @Route("/leasers/leaser/{id}/show", name="leasers_show")
     * @Method("GET")
     */
    public function showLeaserAction(qvLeaser $qvLeaser)
    {
        
        $em = $this->getDoctrine()->getManager();
        $em1 = $this->getDoctrine()->getEntityManager();


         $pquery = $em1->createQuery('SELECT p FROM AppBundle:qvUserPassport p LEFT JOIN p.user u LEFT JOIN u.leaser l WHERE l.id = :id')->setParameter('id', $qvLeaser);
        $qvUserPassport = $pquery->getSingleResult();

        $qvUser = $em->getRepository('AppBundle:qvUser')->findLeaser($qvLeaser);

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
     * @Route("/leasers/company_leaser/{id}/edit", name="company_edit")
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
     * @Route("/leasers/leaser/{id}/editpersonaldata", name="leaser_change")
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
     * Deletes a qvLeaser entity.
     * @Route("/leasers/leaser/{qvUser}/{qvUP}/{qvLeaser}/delete", name="leasers_delete")
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
        $data = array();

        $form = $this->createFormBuilder($data)
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
            ->add('sectors', ChoiceType::class, array(
                'multiple'=>'true',
                'expanded' => 'true',
                'required' => 'true',
                'label' => false,
                'attr' => array('class' => 'form-control type_date-inline'),
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
            
            foreach ($data['sectors'] as $sector) {
            $qvContract->addSectors($sector);
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
 * @Route("/checkpoints_control", name="checkpoints_list")
 * @Method("GET")
 */
    public function checkpointsControlAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $qvCheckpoints = $em->getRepository('AppBundle:qvCheckpoint')->findAll();
        
          $em = $this->getDoctrine()->getEntityManager();
            $query = $em->createQuery(
                'SELECT passport.firstname, passport.lastname, passport.patronimic FROM AppBundle:qvUserPassport passport JOIN passport.user pu  WHERE pu.role = :name'
)->setParameter('name', '4');
$security = $query->getResult();

        return $this->render('AppBundle:AdminBC:checkpoints_control/checkpoints_list.html.twig', array(
        'qvCheckpoints' => $qvCheckpoints,
        'security' => $security,
        ));
    }
    
    
    /**
     * Creates a new qvCheckpoint entity.
     * @ParamConverter("qvBuilding", class="AppBundle:qvBuilding")
     * @Route("/building/{qvBuilding}/checkpoints/create_checkpoint", name="checkpoints_create")
     * @Method({"GET", "POST"})
     */
    public function createCheckpointAction(Request $request, qvBuilding $qvBuilding)
    {
        $qvCheckpoint = new qvCheckpoint();
         $data = array();
        
        $form = $this->createFormBuilder($data)
        ->add('name', TextType::class, array(
            'label' => 'Название КПП',
            'attr'=>array('class'=>'form-control form-input')))
        ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $qvCheckpoint->setName($data['name']);
            $qvCheckpoint->setBuilding($qvBuilding);
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvCheckpoint);
            $em->flush();
            return $this->redirectToRoute('checkpoints_show', array('qvBuilding'=> $qvBuilding->getId(),'id' => $qvCheckpoint->getId()));
        }
        return $this->render('AppBundle:AdminBC:checkpoints_control/create_checkpoint.html.twig', array(
            'qvCheckpoint' => $qvCheckpoint,
            'qvBuilding' => $qvBuilding,
            'form' => $form->createView(),
        ));
    }
    /**
     * Finds and displays a qvCheckpoint entity.
     * @ParamConverter("qvBuilding", class="AppBundle:qvBuilding")
     * @Route("/building/{qvBuilding}/checkpoint/{id}/show", name="checkpoints_show")
     * @Method("GET")
     */
    public function showCheckpointAction(qvCheckpoint $qvCheckpoint, qvBuilding $qvBuilding)
    {
    $deleteForm = $this->createDeleteCheckpointForm($qvCheckpoint);
        
        $roles = $this->getDoctrine()->getManager()->getRepository('AppBundle:qvUser');

           $em = $this->getDoctrine()->getEntityManager();
            $query = $em->createQuery(
                'SELECT passport.firstname, passport.lastname, passport.patronimic FROM AppBundle:qvUserPassport passport JOIN passport.user pu WHERE pu.role = :name'
)->setParameter('name', '4');
$security = $query->getResult();
      

        return $this->render('AppBundle:AdminBC:checkpoints_control/show_checkpoint.html.twig', array(
        'qvCheckpoint' => $qvCheckpoint,
        'qvBuilding' => $qvBuilding,
        'security' => $security,
        'delete_form' => $deleteForm->createView(),
        ));
    }
    
    /**
     * Displays a form to edit an existing qvCheckpoint entity.
     * @ParamConverter("qvBuilding", class="AppBundle:qvBuilding")
     * @Route("/building/{qvBuilding}/checkpoint/{id}/edit", name="checkpoints_edit")
     * @Method({"GET", "POST"})
     */
    public function editCheckpointAction(Request $request, qvCheckpoint $qvCheckpoint, qvBuilding $qvBuilding)
    {
        $deleteForm = $this->createDeleteCheckpointForm($qvCheckpoint);
        $em = $this->getDoctrine()->getManager();
        $qvCheckpoint = $em->getRepository('AppBundle:qvCheckpoint')->findOneById($qvCheckpoint);
        $editForm = $this->createFormBuilder($qvCheckpoint)
        ->add('name', TextType::class, array(
            'label' => 'Название КПП',
            'attr'=>array('class'=>'form-control form-input')))
        ->getForm();

        $editForm->handleRequest($request);
        
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            
            return $this->redirectToRoute('checkpoints_show', array('qvBuilding'=>$qvBuilding->getId(),'id' => $qvCheckpoint->getId()));
        }
        
        return $this->render('AppBundle:AdminBC:checkpoints_control/edit_checkpoint.html.twig', array(
        'qvCheckpoint' => $qvCheckpoint,
        'qvBuilding' =>$qvBuilding,
        'edit_form' => $editForm->createView(),
        'delete_form' => $deleteForm->createView(),
        ));
    }
    
    /**
     * Deletes a qvCheckpoint entity.
        *
     * @Route("/checkpoint/{id}/delete", name="checkpoints_delete")
     * @Method("DELETE")
     */
    public function deleteCheckpointAction(Request $request, qvCheckpoint $qvCheckpoint)
    {
        $form = $this->createDeleteCheckpointForm($qvCheckpoint);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($qvCheckpoint);
            $em->flush();
        }
        
        return $this->redirectToRoute('checkpoints_list');
    }
    
    /**
     * Creates a form to delete a qvCheckpoint entity.
        *
     * @param qvCheckpoint $qvCheckpoint The qvCheckpoint entity
        *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteCheckpointForm(qvCheckpoint $qvCheckpoint)
    {
        return $this->createFormBuilder()
        ->setAction($this->generateUrl('checkpoints_delete', array('id' => $qvCheckpoint->getId())))
        ->setMethod('DELETE')
        ->getForm()
        ;
    }
  /**
     * Lists all qvBuilding entities.
     *
     * @Route("/buildings_control", name="buildings_list")
     * @Method("GET")
     */
    public function buildingsControlAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $qvBuildings = $em->getRepository('AppBundle:qvBuilding')->findAll();

        return $this->render('AppBundle:AdminBC:buildings_control/buildings/buildings_list.html.twig', array(
            'qvBuildings' => $qvBuildings,
        ));
    }
    /**
     * Creates a new qvBuilding entity.
     *
     * @Route("/building/create_building", name="buildings_create")
     * @Method({"GET", "POST"})
     */
    public function buildingCreateAction(Request $request)
    {
        $qvBuilding = new qvBuilding();
        
        $data = array();
        $form = $this->createFormBuilder($data)
        ->add('name', TextType::class, array(
            'label' => 'Название здания',
            'attr'=>array('class'=>'form-control form-input')))
        ->getForm();
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $qvBuilding->setName($data['name']);

            $em = $this->getDoctrine()->getManager();
            $em->persist($qvBuilding);
            $em->flush();
            return $this->redirectToRoute('buildings_list', array('id' => $qvBuilding->getId()));
        }
        return $this->render('AppBundle:AdminBC:buildings_control/buildings/create_building.html.twig', array(
            'qvBuilding' => $qvBuilding,
            'form' => $form->createView(),
        ));
    }
    /**
     * Finds and displays a qvBuilding entity.
     *
     * @Route("/building/{qvBuilding}/show", name="buildings_show")
     * @Method("GET")
     */
    public function showBuildingAction(qvBuilding $qvBuilding)
    {
        $deleteForm = $this->createDeleteBuildingForm($qvBuilding);
        
        $em = $this->getDoctrine()->getManager();

        $count = $em->getRepository('AppBundle:qvFloor')->countFloorInBuild($qvBuilding);
      
        $floors = $em->getRepository('AppBundle:qvFloor')->findFloorsByBuild($qvBuilding);
 
            
        $check = $em->getRepository('AppBundle:qvCheckpoint')
        ->findByBuildingId($qvBuilding);

       // $qvUserPassports = $em->getRepository('AppBundle:qvUserPassport')->findAll();
        
       $usp = $em->getRepository('AppBundle:qvUserPassport')->findAll();


        //->findUserpassportByUserRole();
  /*  if (!$queryFloor)
    {
    throw $this->CreateNotFoundException('Не найдено информации ни по одному этажу');
    }
*/
//else{
        return $this->render('AppBundle:AdminBC:buildings_control/buildings/show_building.html.twig', array(
            'qvBuilding' => $qvBuilding,
            'count' => $count,
            'floors' => $floors,
            'check' => $check,
            'usp' => $usp,  
            'delete_form' => $deleteForm->createView(),
        ));
    //}
}
    /**
     * Displays a form to edit an existing qvBuilding entity.
     *
     * @Route("/building/{qvBuilding}/edit", name="buildings_edit")
     * @Method({"GET", "POST"})
     */
    public function editBuildingAction(Request $request, qvBuilding $qvBuilding)
    {
        $deleteForm = $this->createDeleteBuildingForm($qvBuilding);

        $editForm = $this->createFormBuilder($qvBuilding)
        ->add('name', TextType::class, array(
            'label'=> 'Наименование здания',
            'attr'=> array('class'=> 'form-control form-margin')))
        ->getForm();
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('buildings_list', array('id' => $qvBuilding->getId()));
        }
        return $this->render('AppBundle:AdminBC:buildings_control/buildings/edit_building.html.twig', array(
            'qvBuilding' => $qvBuilding,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a qvBuilding entity.
     *
     * @Route("/building/{id}/delete", name="buildings_delete")
     * @Method("DELETE")
     */
    public function deleteBuildingAction(Request $request, qvBuilding $qvBuilding)
    {
        $form = $this->createDeleteBuildingForm($qvBuilding);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($qvBuilding);
            $em->flush();
        }
        return $this->redirectToRoute('buildings_list');
    }
    /**
     * Creates a form to delete a qvBuilding entity.
     *
     * @param qvBuilding $qvBuilding The qvBuilding entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteBuildingForm(qvBuilding $qvBuilding)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('buildings_delete', array('id' => $qvBuilding->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
/**
     * Lists all qvFloor entities.
     *
     * @Route("/building/{qvBuilding}/floors_control", name="floors_list")
     * @ParamConverter("qvBuilding", class="AppBundle:qvBuilding")
     * @Method("GET")
     */
    public function floorsControlAction()
    {
        $em = $this->getDoctrine()->getManager();
        $qvFloors = $em->getRepository('AppBundle:qvFloor')->findAll();
        
        return $this->render('AppBundle:AdminBC:buildings_control/floors/floors_list.html.twig', array(
            'qvFloors' => $qvFloors,
        ));
    }
    /**
     * Creates a new qvFloor entity.
     *
     * @Route("/building/{qvBuilding}/floors/new_floor", name="floors_create")
     * @ParamConverter("qvBuilding", class="AppBundle:qvBuilding")
     * @Method({"GET", "POST"})
     */
    public function createFloorAction(Request $request, qvBuilding $qvBuilding)
    {
        $qvFloor = new qvFloor();
        $data = array();
        
        $form = $this->createFormBuilder($data)
        ->add('name', TextType::class, array(
            'label' => 'Название этажа',
            'attr'=>array('class'=>'form-control form-input')))
        ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $qvFloor->setName($data['name']);
            $qvFloor->setBuilding($qvBuilding);
            $em->persist($qvFloor);
            $em->flush();

            return $this->redirectToRoute('floors_show', array('qvBuilding' => $qvBuilding->getId(),'id' => $qvFloor->getId()));
            }
        return $this->render('AppBundle:AdminBC:buildings_control/floors/create_floor.html.twig', array(
            'qvFloor' => $qvFloor,
            'qvBuilding' => $qvBuilding,
            'form' => $form->createView(),
        ));
    }
    /**
     * Finds and displays a qvFloor entity.
     *
     * @Route("/building/{qvBuilding}/floors/{id}/show", name="floors_show")
     * @ParamConverter("qvBuilding", class="AppBundle:qvBuilding")
     * @Method("GET")
     */
    public function showFloorAction(qvFloor $qvFloor, qvBuilding $qvBuilding)
    {
        $deleteForm = $this->createDeleteFloorForm($qvFloor);
        $em = $this->getDoctrine()->getManager();
        $sector = $em->getRepository('AppBundle:qvFloor')->findSectorByFloor($qvFloor);
      
        return $this->render('AppBundle:AdminBC:buildings_control/floors/show_floor.html.twig', array(
            'qvFloor' => $qvFloor,
            'qvBuilding'=>$qvBuilding,
            'sector' => $sector,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Displays a form to edit an existing qvFloor entity.
     *
     * @Route("/building/{qvBuilding}/floors/{id}/edit", name="floors_edit")
     * @ParamConverter("qvBuilding", class="AppBundle:qvBuilding")
     * @Method({"GET", "POST"})
     */
    public function editFloorAction(Request $request, qvFloor $qvFloor, 
        qvBuilding $qvBuilding)
    {
        $deleteForm = $this->createDeleteFloorForm($qvFloor);
          $editForm = $this->createFormBuilder($qvFloor)
        ->add('name', TextType::class, array(
            'label' => 'Название этажа',
            'attr'=>array('class'=>'form-control form-input')))
        ->getForm();
       
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('buldings_show', array('qvBuilding' => 
                $qvBuilding->getId()));
        }
        return $this->render('AppBundle:AdminBC:buildings_control/floors/edit_floor.html.twig', array(
            'qvFloor' => $qvFloor,
            'qvBuilding' => $qvBuilding,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a qvFloor entity.
     * @ParamConverter("qvBuilding", class="AppBundle:qvBuilding")
     * @Route("/floors/{id}/delete", name="floors_delete")
     * @Method("DELETE")
     */
    public function deleteFloorAction(Request $request, qvFloor $qvFloor, qvBuilding $qvBuilding)
    {
        $form = $this->createDeleteFloorForm($qvFloor);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($qvFloor);
            $em->flush();
        }
        return $this->redirectToRoute('buildings_show', array('qvBuilding'=>$qvBuilding->getId()));
    }

    /**
     * Creates a form to delete a qvFloor entity.
     *
     * @param qvFloor $qvFloor The qvFloor entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteFloorForm(qvFloor $qvFloor)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('floors_delete', array('id' => $qvFloor->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }


    /**
     * Deletes a qvFloor entity.
     * @ParamConverter("qvBuilding", class="AppBundle:qvBuilding")
     * @Route("/{qvBuilding}/floor/{qvFloor}/delete", name="floor_deleting")
     * @Method({"GET", "POST"})
     */

    public function deleteFloorShowAction(Request $request, qvBuilding $qvBuilding, qvFloor $qvFloor)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($qvFloor);
        $em->flush();
        return $this->redirectToRoute('buildings_show', array('qvBuilding'=>$qvBuilding->getId()));
    }
 /**
     * Lists all qvSector entities.
     *
     * @Route("/building/{qvBuilding}/floor/{qvFloor}/sectors", name="sectors_list")
     * @ParamConverter("qvFloor", class="AppBundle:qvFloor")
     * @ParamConverter("qvBuilding", class="AppBundle:qvBuilding")
     * @Method("GET")
     */
    public function listSectorsAction(qvBuilding $qvBuilding, qvFloor $qvFloor)
    {
        $em = $this->getDoctrine()->getManager();
        $qvSectors = $em->getRepository('AppBundle:qvSector')->findAll();
        return $this->render('AppBundle:AdminBC:buildings_control/sectors/sectrors_list.html.twig', array(
            'qvSectors' => $qvSectors,
        ));
    }
    /**
     * Creates a new qvSector entity.
     *
     * @Route("/building/{qvBuilding}/floor/{qvFloor}/sectors/new_sector", name="sectors_create")
     * @ParamConverter("qvFloor", class="AppBundle:qvFloor")
     * @ParamConverter("qvBuilding", class="AppBundle:qvBuilding")
     * @Method({"GET", "POST"})
     */
    public function createSectorAction(Request $request, qvBuilding $qvBuilding, qvFloor $qvFloor)
    {
        $qvSector = new qvSector();
        $data = array();

        $em = $this->getDoctrine()->getManager();
        $em->getConnection()->beginTransaction(); 
try {
        $form = $this->createFormBuilder($data)
        ->add('name', TextType::class, array(
            'label'=>'Название сектора',
            'attr'=>array('class'=>'form-control form-input ')))
        ->getForm();
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $data = $form->getData();
            
            $qvSector->setName($data['name']);
            $qvSector->setFloor($qvFloor);

            $em->persist($qvSector);
            $em->flush();
            $em->getConnection()->commit();
            return $this->redirectToRoute('floors_show', array('qvBuilding' => $qvBuilding->getId(), 'id' => $qvFloor->getId()));
 
}
} catch (Exception $e) {
    $em->getConnection()->rollBack();
    throw $e;
}
        
        return $this->render('AppBundle:AdminBC:buildings_control/sectors/create_sector.html.twig', array(
            'qvSector' => $qvSector,
            'qvFloor' => $qvFloor,
            'qvBuilding' =>$qvBuilding,
            'form' => $form->createView(),
        ));
    }
    /**
     * Finds and displays a qvSector entity.
     *
     * @Route("/building/{qvBuilding}/floor/{qvFloor}/sector/{id}/show", name="sectors_show")
     * @ParamConverter("qvFloor", class="AppBundle:qvFloor")
     * @ParamConverter("qvBuilding", class="AppBundle:qvBuilding")
     * @Method("GET")
     */
    public function showSectorAction(qvSector $qvSector, qvBuilding $qvBuilding, qvFloor $qvFloor)
    {
        $deleteForm = $this->createDeleteSectorForm($qvSector, $qvBuilding, $qvFloor);
        $em = $this->getDoctrine()->getManager();
        return $this->render('AppBundle:AdminBC:buildings_control/sectors/show_sector.html.twig', array(
            'qvSector' => $qvSector,
            'qvFloor' =>$qvFloor,
            'qvBuilding'=>$qvBuilding,
        'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Displays a form to edit an existing qvSector entity.
     *
     * @Route("/building/{qvBuilding}/floor/{qvFloor}/sector/{id}/edit", name="sectors_edit")
     * @ParamConverter("qvBuilding", class="AppBundle:qvBuilding")
     * @ParamConverter("qvFloor", class="AppBundle:qvFloor")
     * @Method({"GET", "POST"})
     */
    public function editSectorAction(Request $request, qvSector $qvSector, qvBuilding $qvBuilding, qvFloor $qvFloor)
    {
        $deleteForm = $this->createDeleteSectorForm($qvSector, $qvBuilding, $qvFloor);
         $em = $this->getDoctrine()->getManager();
        $editForm = $this->createFormBuilder($qvSector)
        ->add('name', TextType::class, array(
            'label'=>'Название сектора',
            'attr'=> array('class'=>'form-control')))
        ->getForm();

        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();
            return $this->redirectToRoute('floors_show', 
               array('qvBuilding' => $qvBuilding->getId(), 'id' => $qvFloor->getId()));
        }
        return $this->render('AppBundle:AdminBC:buildings_control/sectors/edit_sector.html.twig', array(
            'qvSector' => $qvSector,
            'qvBuilding'=>$qvBuilding,
            'qvFloor'=>$qvFloor,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a qvSector entity.
     * @Route("/{qvBuilding}/{qvFloor}/sector/{id}/delete", name="sectors_delete")
     * @ParamConverter("qvBuilding", class="AppBundle:qvBuilding")
     * @ParamConverter("qvFloor", class="AppBundle:qvFloor")
     * @Method("DELETE")
    */
    public function deleteSectorAction(Request $request, qvSector $qvSector, qvBuilding $qvBuilding, qvFloor $qvFloor)
    {
        $form = $this->createDeleteSectorForm($qvSector, $qvBuilding, $qvFloor);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($qvSector);
            $em->flush();
        }
        return $this->redirectToRoute('floors_show', array('qvBuilding' => $qvBuilding->getId(), 'id' => $qvFloor->getId()));
    }
    /**
     * Creates a form to delete a qvSector entity.
     *
     * @param qvSector $qvSector The qvSector entity
     * @ParamConverter("qvBuilding", class="AppBundle:qvBuilding")
     * @ParamConverter("qvFloor", class="AppBundle:qvFloor")
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteSectorForm(qvSector $qvSector,qvBuilding $qvBuilding, qvFloor $qvFloor)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sectors_delete', array('qvBuilding' => $qvBuilding->getId(), 'qvFloor' => $qvFloor->getId(), 'id' => $qvSector->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

      /**
     * Deletes a qvSector entity.
     * @ParamConverter("qvBuilding", class="AppBundle:qvBuilding")
     * @ParamConverter("qvFloor", class="AppBundle:qvFloor")
     * @Route("/{qvBuilding}/floor/{qvFloor}/{qvSector}/delete", name="sector_deleting")
     * @Method({"GET", "POST"})
     */

    public function deleteSectorShowAction(Request $request, qvBuilding $qvBuilding, qvFloor $qvFloor, qvSector $qvSector)
    {
        $em = $this->getDoctrine()->getManager();
        $em->getConnection()->beginTransaction(); 
try {
        $em->remove($qvSector);
        $em->flush();
        return $this->redirectToRoute('floors_show', array('qvBuilding'=>$qvBuilding->getId(), 'id'=>$qvFloor->getId()));
     $em->getConnection()->commit();
} catch (Exception $e) {
    $em->getConnection()->rollBack();
    throw $e;
}
}

     /**
     * Lists all qvUserPassport entities.
     *
     * @Route("/security_control", name="security_list")
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
     * @Route("/security/new_security", name="new_security")
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
            //'qvUserPassport' => $qvUserPassport,
            //'qvUser' => $qvUser,
            //'data' => $data,
            'form' => $form->createView(),
        ));   

    }

    /**
     * Finds and displays a qvUserPassport entity.
     * @Route("/{qvUser}/security/{id}/show", name="show_security")
     * @Method("GET")
     */
    public function showSecurityAction(qvUser $qvUser,qvUserPassport $qvUserPassport, $id)
    {
        $em = $this->getDoctrine()->getManager();
        //$qvUser = new qvUser();
        //$user = $em->getRepository('AppBundle:qvUserPassport')->findUserByPassport($qvUserPassport);
        
        $deleteForm = $this->createDeleteSecurityForm($qvUser, $qvUserPassport);
       // $role = new qvRole();
        //$role->setCode('ROLE_CHECKPOINT');
        //$qvUser->setDisabled($user['disabled']);
        //$qvUser->setRole($role);
        $res = $qvUser->getDisabled();
        if($res == 1)
        {
            return $this->render('AppBundle:AdminBC:error.html.twig', array('qvUser'=>$qvUser));
        }
    else{
        return $this->render('AppBundle:AdminBC:checkpoints_control/security_man/show.html.twig', array(
            'qvUserPassport' => $qvUserPassport,
            'qsvUser' => $qvUser,
            //'user'=>$user,
            'delete_form' => $deleteForm->createView(),
        ));
    }
}
    /**
     * Displays a form to edit an existing qvUserPassport entity.
     *
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
         }
     }
    catch (Exception $e) {
            $em->getConnection()->rollBack();
    throw $e;
}
        return $this->redirectToRoute('security_list');
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

      /**
     * @Route("/analytics/visitors", name = "attendece-visitors")
     * @Method("GET")
     */
    public function AnalyticsAttendanceVisitorsAction()
    {   
    $em = $this->getDoctrine()->getManager();
    
    $currentDate = new \Datetime("UTC");

    $monthdate = date("Y-m-d", mktime(0, 0, 0, date('m'), date('d') - 30, date('Y')));
    $month = '30';
    
    $result = array();

    $emm = $this->getDoctrine()->getEntityManager();
            $query = $emm->createQuery('SELECT count(e) AS rank, SUBSTRING(e.entrancedate, 0, 12) as month, COUNT(e.visitor) AS visitorscount FROM AppBundle:qvEntrance e WHERE month <=  :currentdate and month >= :monthdate GROUP BY month order by month')->setParameters(array('currentdate'=> $currentDate->format('Y-m-d'), 'monthdate'=>$monthdate));
            $data = $query->getResult();

            $query2 = $emm->createQuery('SELECT e.id as num, e.entrancedate as day FROM AppBundle:qvEntrance e WHERE e.entrancedate <=  :currentdate group by e.entrancedate')->setParameter('currentdate', $currentDate);
            $dates = $query2->getResult();
         //   var_dump($data);    
                
    // Chart
        foreach ($data as $i) {
            $a = array($i['rank'], intval($i['visitorscount']));
            array_push($result, $a);
            }
     $series = array(
        array("name" => "Количество посетителей", "data" => $result)
    );

   $ob = new Highchart();
$ob->chart->renderTo('container');
$ob->title->text('Browser market shares at a specific website in 2010');
$ob->plotOptions->pie(array(
    'allowPointSelect'  => true,
    'cursor'    => 'pointer',
    'dataLabels'    => array('enabled' => false),
    'showInLegend'  => true
));
$data = array(
    array('Firefox', 45.0),
    array('IE', 26.8),
    array('Chrome', 12.8),
    array('Safari', 8.5),
    array('Opera', 6.2),
    array('Others', 0.7),
);
$ob->series(array(array('type' => 'pie','name' => 'Browser share', 'data' => $data)));

     return $this->render('AppBundle:AdminBC:Analytics/visitorsByLeasers.html.twig', array(
        'chart' => $ob,
        'data' => $result,
        'currentDate'=>$currentDate,
        'monthdate'=>$monthdate,
    ));
    }
  
       /**
     * @Route("/analytics/visitorsbyorders", name = "visitorsbyorders")
     * @Method("GET")
     */
    public function AnalyticsVisitorsByOrdersAction()
    {
            return $this->render('AppBundle:AdminBC:Analytics/visitorsByOrders.html.twig', array(
        ));
    }
  
       /**
     * @Route("/analytics/dependenceVisitors", name = "dependenceVisitors")
     * @Method("GET")
     */
    public function AnalyticsDependenceVisitorsAction()
    {
            return $this->render('AppBundle:AdminBC:Analytics/visitorsAndSectors.html.twig', array(
        ));
    }
  
 /**
     * @Route("/analytics/attendance", name = "attendance")
     * @Method("GET")
     */

public function chartAction()
{
    $em = $this->getDoctrine()->getManager();
    
    $currentDate = new \Datetime("UTC");

    $monthdate = date("Y-m-d", mktime(0, 0, 0, date('m'), date('d') - 30, date('Y')));
    $month = '30';
    /*$maxVisitors = $this->getDoctrine()->getEntityManager()->
    createQuery('SELECT COUNT(e.visitor) FROM AppBundle:qvEntrance e WHERE e.entrancedate <  :currentdate or e.entrancedate =  :currentdate and e.entrancedate > :monthdate or e.entrancedate = :monthdate')->setParameters(array('currentdate'=> $currentDate, 'monthdate'=>($monthdate)))->getSingleScalarResult();
    */      
    $result = array();

    $emm = $this->getDoctrine()->getEntityManager();
            $query = $emm->createQuery('SELECT count(e) AS rank, SUBSTRING(e.entrancedate, 0, 12) as month, COUNT(e.visitor) AS visitorscount FROM AppBundle:qvEntrance e WHERE month <=  :currentdate and month >= :monthdate GROUP BY month order by month')->setParameters(array('currentdate'=> $currentDate->format('Y-m-d'), 'monthdate'=>$monthdate));
            $data = $query->getResult();

            $query2 = $emm->createQuery('SELECT e.id as num, e.entrancedate as day FROM AppBundle:qvEntrance e WHERE e.entrancedate <=  :currentdate group by e.entrancedate')->setParameter('currentdate', $currentDate);
            $dates = $query2->getResult();
         //   var_dump($data);    
                
    // Chart
        foreach ($data as $i) {
            $a = array($i['rank'], intval($i['visitorscount']));
            array_push($result, $a);
            }
     $series = array(
        array("name" => "Количество посетителей", "data" => $result)
    );

    $ob = new Highchart();
    $ob->chart->renderTo('container');  // The #id of the div where to render the chart
    //$ob->chart->type('spline');
    $ob->title->text('График посещаемости БЦ');
    $ob->xAxis->type('date');
    $ob->xAxis->title(array('text'  => "Период времени"));
    $ob->xAxis->data(array(array("data"=>$dates)));
    $ob->xAxis->dateTimeLabelFormats(array('month'=> '%e. %b', 'year'=> '%b'));
    

    $ob->yAxis->title(array('text'  => "Количество посетителей"));
    $ob->series($series);

    return $this->render('AppBundle:AdminBC:Analytics/attendance.html.twig', array(
        'chart' => $ob,
        'data' => $result,
        'currentDate'=>$currentDate,
        'monthdate'=>$monthdate,
    ));
}
}
    
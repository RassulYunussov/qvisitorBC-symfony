<?php
namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
use AppBundle\Entity\UserAccount;
use AppBundle\Form\UserAccountType;



/**
 * adminBCController 
 * 
 * @Route("/adminbc")
 * 
 */
class AdminBCController extends Controller
{
    /**
     * @Route("/index", name = "main_page")
     * @Method("GET")
     */
    public function adminbcAction()
    {
            return $this->render('AppBundle:Adminbc:index.html.twig', array(
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
        
        return $this->render('AppBundle:AdminBC:leasers_control/leasers/leasers_list.html.twig', array(
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
        
        $em = $this->getDoctrine()->getManager();

         $data = array();

         $form = $this->createFormBuilder($data)
            ->add('name', TextType::class)
            ->add('bin', NumberType::class)
            ->add('login', TextType::class)
            ->add('password', PasswordType::class)
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('patronimic', TextType::class)
            ->add('birthdate', BirthdayType::class, array(
                'placeholder' => array(
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                )
            )
        )
            ->add('gender',  EntityType::class, array(
                'class' => 'AppBundle\Entity\qvGender')
            )
            ->getForm()
        ;
        $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
          
           $em = $this->getDoctrine()->getManager();

           $myrole = $em->getRepository('AppBundle:qvRole')->findByCodeLeaser();

           $data = $form->getData();
            
            $qvLeaser->SetName($data['name']);
            $qvLeaser->SetBin($data['bin']);
            
            $qvUser->setLogin($data['login']);   
            $qvUser->setPassword($data['password']);
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

            return $this->redirectToRoute('lesers_list', array());
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
        $deleteForm = $this->createDeleteLeaserForm($qvLeaser);
        $em = $this->getDoctrine()->getManager();
        
         $em1 = $this->getDoctrine()->getEntityManager();
         $queryContract = $em->createQuery(
            'SELECT cnr.name, cnr.id from AppBundle:qvContract cnr WHERE cnr.leaser = :name'
            )->setParameter('name', $qvLeaser);
            $contracts = $queryContract->getResult();
            if (!$contracts)
            {
        $contracts = 'Не найдено ни одного контракта';
            }
        return $this->render('AppBundle:AdminBC:leasers_control/leasers/show_leaser.html.twig', array(
            'qvLeaser' => $qvLeaser,
            'contracts' => $contracts,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    /**
     * Displays a form to edit an existing qvLeaser entity.
        *
     * @Route("/leasers/leaser/{id}/edit", name="leasers_edit")
     * @Method({"GET", "POST"})
     */
    public function editLeaserAction(Request $request, qvLeaser $qvLeaser)
    {
        $deleteForm = $this->createDeleteLeaserForm($qvLeaser);
        $editForm = $this->createForm('AppBundle\Form\qvLeaserType', $qvLeaser);
        $editForm->handleRequest($request);
        
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvLeaser);
            $em->flush();
            
            return $this->redirectToRoute('leasers_list', array('id' => $qvLeaser->getId()));
        }
        return $this->render('AppBundle:AdminBC:leasers_control/leasers/edit_leaser.html.twig', array(
        'qvLeaser' => $qvLeaser,
        'edit_form' => $editForm->createView(),
        'delete_form' => $deleteForm->createView(),
        ));
    }
    
    /**
     * Deletes a qvLeaser entity.
        *
     * @Route("/leasers/leaser/{id}/delete", name="leasers_delete")
     * @Method("DELETE")
     */
    public function deleteLeaserAction(Request $request, qvLeaser $qvLeaser)
    {
        $form = $this->createDeleteLeaserForm($qvLeaser);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($qvLeaser);
            $em->flush();
        }
    return $this->redirectToRoute('leasers_list', array('id' => $qvLeaser->getId()
        ));
    }
    
   
    /**
     * Creates a form to delete a qvLeaser entity.
        *
     * @param qvLeaser $qvLeaser The qvLeaser entity
        *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteLeaserForm(qvLeaser $qvLeaser)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('leasers_delete', array('id' => $qvLeaser->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    
    
     /**
     * @Route("/leasers/contracts", name="contracts_list")
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
     * @Route("/leasers/contract/create_contract", name="contracts_create")
      * @Method({"GET", "POST"})
     */
    public function contractCreateAction(Request $request)
    {
        $qvContract = new qvContract();
        $form = $this->createForm('AppBundle\Form\qvContractType', $qvContract);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvContract);
            $em->flush();
            return $this->redirectToRoute('contracts_index', array('id' => $qvContract->getId()));
        }
        return $this->render('AppBundle:Adminbc:leasers_control/contracts/create_contract.html.twig', array(
            'qvContract' => $qvContract,
            'form' => $form->createView(),
        ));
    }
     /**
     * Finds and displays a qvContract entity.
     *
     * @Route("/leasers/contract/{id}/show", name="contracts_show")
     * @Method("GET")
     */
    public function showContractAction(qvContract $qvContract)
    {
        $deleteForm = $this->createDeleteContractForm($qvContract);
        
        return $this->render('AppBundle:Adminbc:leasers_control/contracts/show_contract.html.twig', array(
            'qvContract' => $qvContract,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Displays a form to edit an existing qvContract entity.
     *
     * @Route("/leasers/contract/{id}/edit", name="contracts_edit")
     * @Method({"GET", "POST"})
     */
    public function editContractAction(Request $request, qvContract $qvContract)
    {
        $deleteForm = $this->createDeleteContractForm($qvContract);
        $editForm = $this->createForm('AppBundle\Form\qvContractType', $qvContract);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvContract);
            $em->flush();
            return $this->redirectToRoute('contracts_list', array('id' => $qvContract->getId()));
        }
        return $this->render('AppBundle:Adminbc:leasers_control/contracts/edit_contract.html.twig', array(
            'qvContract' => $qvContract,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a qvContract entity.
     *
     * @Route("/leasers/contract/{id}/delete", name="contracts_delete")
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
        return $this->redirectToRoute('contracts_list');
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
     *
     * @Route("/checkpoint/create_checkpoint", name="checkpoints_create")
     * @Method({"GET", "POST"})
     */
    public function createCheckpointAction(Request $request)
    {
        $qvCheckpoint = new qvCheckpoint();
        $form = $this->createForm('AppBundle\Form\qvCheckpointType', $qvCheckpoint);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvCheckpoint);
            $em->flush();
            return $this->redirectToRoute('checkpoints_show', array('id' => $qvCheckpoint->getId()));
        }
        return $this->render('AppBundle:Adminbc:checkpoints_control/create_checkpoint.html.twig', array(
            'qvCheckpoint' => $qvCheckpoint,
            'form' => $form->createView(),
        ));
    }
    /**
     * Finds and displays a qvCheckpoint entity.
     *
     * @Route("/checkpoint/{id}/show", name="checkpoints_show")
     * @Method("GET")
     */
    public function showCheckpointAction(qvCheckpoint $qvCheckpoint)
    {
    $deleteForm = $this->createDeleteCheckpointForm($qvCheckpoint);
        
        $roles = $this->getDoctrine()->getManager()->getRepository('AppBundle:qvUser');

           $em = $this->getDoctrine()->getEntityManager();
            $query = $em->createQuery(
                'SELECT passport.firstname, passport.lastname, passport.patronimic FROM AppBundle:qvUserPassport passport JOIN passport.user pu WHERE pu.role = :name'
)->setParameter('name', '4');
$security = $query->getResult();
      

        return $this->render('AppBundle:Adminbc:checkpoints_control/show_checkpoint.html.twig', array(
        'qvCheckpoint' => $qvCheckpoint,
        'security' => $security,
        'delete_form' => $deleteForm->createView(),
        ));
    }
    
    /**
     * Displays a form to edit an existing qvCheckpoint entity.
        *
     * @Route("/checkpoint/{id}/edit", name="checkpoints_edit")
     * @Method({"GET", "POST"})
     */
    public function editCheckpointAction(Request $request, qvCheckpoint $qvCheckpoint)
    {
        $deleteForm = $this->createDeleteCheckpointForm($qvCheckpoint);
        $editForm = $this->createForm('AppBundle\Form\qvCheckpointType', $qvCheckpoint);
        $editForm->handleRequest($request);
        
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvCheckpoint);
            $em->flush();
            
            return $this->redirectToRoute('checkpoints_list', array('id' => $qvCheckpoint->getId()));
        }
        
        return $this->render('AppBundle:Adminbc:checkpoints_control/edit_checkpoint.html.twig', array(
        'qvCheckpoint' => $qvCheckpoint,
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
        $form = $this->createForm('AppBundle\Form\qvBuildingType', $qvBuilding);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
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
     * @Route("/building/{id}/show", name="buildings_show")
     * @Method("GET")
     */
    public function showBuildingAction(qvBuilding $qvBuilding)
    {
        $deleteForm = $this->createDeleteBuildingForm($qvBuilding);
        
        $em = $this->getDoctrine()->getManager();

        $count = $em->getRepository('AppBundle:qvFloor')->countFloorInBuild($qvBuilding);
      
        $floors = $em->getRepository('AppBundle:qvFloor')->findFlooorByBuild($qvBuilding);
 
        $sectorlist = $em->getRepository('AppBundle:qvSector')->findAll();
              
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
            'sectorlist' => $sectorlist,
            'check' => $check,
            'usp' => $usp,  
            'delete_form' => $deleteForm->createView(),
        ));
    //}
}
    /**
     * Displays a form to edit an existing qvBuilding entity.
     *
     * @Route("/building/{id}/edit", name="buildings_edit")
     * @Method({"GET", "POST"})
     */
    public function editBuildingAction(Request $request, qvBuilding $qvBuilding)
    {
        $deleteForm = $this->createDeleteBuildingForm($qvBuilding);
        $editForm = $this->createForm('AppBundle\Form\qvBuildingType', $qvBuilding);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvBuilding);
            $em->flush();
            return $this->redirectToRoute('buildings_show', array('id' => $qvBuilding->getId()));
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
     * @Route("building/floors_control", name="floors_list")
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
     * @Route("/building/{id}/floors/new_floor", name="floors_create")
     * @Method({"GET", "POST"})
     */
    public function createFloorAction(Request $request,qvBuilding $qvBuilding)
    {
        $qvFloor = new qvFloor();
        $qvFloor->setBuilding($qvBuilding);
        $form = $this->createFormBuilder('AppBundle\Form\qvFloorType', $qvFloor);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvFloor);
            $em->flush();
<<<<<<< HEAD
            return $this->redirectToRoute('floors_show', array(array('idb' => 
                $qvBuilding->getId(), 'id' => $qvFloor->getId())));
=======
            return $this->redirectToRoute('buildings_show', array('id' => $qvBuilding->getId(), 'active_page'=>'floor'));
>>>>>>> 3540b5302ab57cf0bf9b2595446263780e021e8c
        }
        return $this->render('AppBundle:AdminBC:buildings_control/floors/create_floor.html.twig', array(
            'qvFloor' => $qvFloor,
            'form' => $form->createView(),
        ));
    }
    /**
     * Finds and displays a qvFloor entity.
     *
     * @Route("/building/{idb}/floors/{id}/show", name="floors_show")
     * @Method("GET")
     */
    public function showFloorAction(qvFloor $qvFloor)
    {
        $deleteForm = $this->createDeleteFloorForm($qvFloor);
        $em = $this->getDoctrine()->getManager();
        $sector = $em->getRepository('AppBundle:qvFloor')->findSectorByFloor($qvFloor);
        $build = $em->getRepository('AppBundle:qvFloor')->findBuildByFloor($qvFloor);

        return $this->render('AppBundle:AdminBC:buildings_control/floors/show_floor.html.twig', array(
            'qvFloor' => $qvFloor,
            'sector' => $sector,
            'build' => $build,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Displays a form to edit an existing qvFloor entity.
     *
     * @Route("/building/{idb}/floors/{id}/edit", name="floors_edit")
     * @Method({"GET", "POST"})
     */
    public function editFloorAction(Request $request, qvFloor $qvFloor)
    {
        $deleteForm = $this->createDeleteFloorForm($qvFloor);
        $editForm = $this->createForm('AppBundle\Form\qvFloorType', $qvFloor);
       
        $qvBuilding = $this->getDoctrine()->getManager()->getRepository('AppBundle:qvFloor')->findBuildByFloor($qvFloor);

        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvFloor);
            $em->flush();
            return $this->redirectToRoute('floors_show', array('idb' => 
                $qvBuilding->getId(), 'id' => $qvFloor->getId()));
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
     *
     * @Route("/floors/{id}/delete", name="floors_delete")
     * @Method("DELETE")
     */
    public function deleteFloorAction(Request $request, qvFloor $qvFloor)
    {
        $form = $this->createDeleteFloorForm($qvFloor);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($qvFloor);
            $em->flush();
        }
        return $this->redirectToRoute('floors_list');
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
     * Lists all qvSector entities.
     *
     * @Route("/sectors_control", name="sectors_list")
     * @Method("GET")
     */
    public function listSectorsAction()
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
     * @Route("/floor/{id}/sector/new", name="sectors_create")
     * @Method({"GET", "POST"})
     */
    public function createSectorAction(Request $request, qvFloor $qvFloor)
    {
        $qvSector = new qvSector();
        $qvSector->setFloor($qvFloor);
        $form = $this->createForm('AppBundle\Form\qvSectorType', $qvSector);
        $form->handleRequest($request);
       // $build = $this->getDoctrine()->getEntityManager
       
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvSector);
            $em->flush();
            return $this->redirectToRoute('sectors_show', array('id' => $qvSector->getId()));
        }



        return $this->render('AppBundle:AdminBC:buildings_control/sectors/create_sector.html.twig', array(
            'qvSector' => $qvSector,
            'qvFloor' => $qvFloor,
            'form' => $form->createView(),
        ));
    }
    /**
     * Finds and displays a qvSector entity.
     *
     * @Route("/sector/{id}/show", name="sectors_show")
     * @Method("GET")
     */
    public function showSectorAction(qvSector $qvSector)
    {
        $deleteForm = $this->createDeleteSectorForm($qvSector);
        $em = $this->getDoctrine()->getManager();

       // $blid = $em->getRepository('AppBundle:qvSector')->findBuildById($qvSector);

 //       $flid = $em->getRepository('AppBundle:qvSector')->findFloorById($qvSector);
//
        return $this->render('AppBundle:AdminBC:buildings_control/sectors/show_sector.html.twig', array(
            'qvSector' => $qvSector,
  //          'blid' => $blid,
    //          'flid' => $flid,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Displays a form to edit an existing qvSector entity.
     *
     * @Route("/sector/{id}/edit", name="sectors_edit")
     * @Method({"GET", "POST"})
     */
    public function editSectorAction(Request $request, qvSector $qvSector)
    {
        $deleteForm = $this->createDeleteSectorForm($qvSector);
        $editForm = $this->createForm('AppBundle\Form\qvSectorType', $qvSector);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvSector);
            $em->flush();
            return $this->redirectToRoute('sectors_list', array('id' => $qvSector->getId()));
        }
        return $this->render('AppBundle:AdminBC:buildings_control/sectors/edit_sector.html.twig', array(
            'qvSector' => $qvSector,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a qvSector entity.
     *
     * @Route("/sector/{id}/delete", name="sectors_delete")
     * @Method("DELETE")
     */
    public function deleteSectorAction(Request $request, qvSector $qvSector)
    {
        $form = $this->createDeleteSectorForm($qvSector);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($qvSector);
            $em->flush();
        }
        return $this->redirectToRoute('sectors_list');
    }
    /**
     * Creates a form to delete a qvSector entity.
     *
     * @param qvSector $qvSector The qvSector entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteSectorForm(qvSector $qvSector)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sectors_delete', array('id' => $qvSector->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

     /**
     * Lists all qvUserPassport entities.
     *
     * @Route("/security_control", name="security_list")
     * @Method("GET")
     */
     public function indexSecurityAction()
    {
        $em = $this->getDoctrine()->getManager();

        $qvUserPassports = $em->getRepository('AppBundle:qvUserPassport')->findAll();
        
        $em = $this->getDoctrine()->getEntityManager();
            $query = $em->createQuery(
                'SELECT passport.id, passport   .firstname, passport.lastname, passport.patronimic, passport.birthdate FROM AppBundle:qvUserPassport passport JOIN passport.user pu WHERE pu.role = :name'
                    )->setParameter('name', '4');

            $usp = $query->getResult();

        return $this->render('AppBundle:AdminBC:checkpoints_control/security_man/index.html.twig', array(
            'qvUserPassports' => $qvUserPassports,
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

         $data = array();

         $form = $this->createFormBuilder($data)
            ->add('login', TextType::class)
            ->add('password', PasswordType::class)
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('patronimic', TextType::class)
            ->add('birthdate', BirthdayType::class, array(
                'placeholder' => array(
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                )
            )
        )
            ->add('gender',  EntityType::class, array(
                'class' => 'AppBundle\Entity\qvGender')
            )
            ->getForm()
        ;
        $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
          
           $em = $this->getDoctrine()->getManager();

           $myrole = $em->getRepository('AppBundle:qvRole')->findByCodeCheckpoint('ROLE_CHECKPOINT');

           $data = $form->getData();
         
            $qvUser->setLogin($data['login']);   
            $qvUser->setPassword($data['password']);
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

            return $this->redirectToRoute('security_list', array());
        }
        return $this->render('AppBundle:AdminBC:checkpoints_control/security_man/new.html.twig', array(
            'qvUserPassport' => $qvUserPassport,
            'qvUser' => $qvUser,
            'data' => $data,
            'form' => $form->createView(),
        ));   

    }

    /**
     * Finds and displays a qvUserPassport entity.
     *
     * @Route("/security/{id}/show", name="show_security")
     * @Method("GET")
     */
    public function showSecurityAction(qvUserPassport $qvUserPassport)
    {
        $deleteForm = $this->createDeleteSecurityForm($qvUserPassport);

        return $this->render('AppBundle:AdminBC:checkpoints_control/security_man/show.html.twig', array(
            'qvUserPassport' => $qvUserPassport,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing qvUserPassport entity.
     *
     * @Route("/security/{id}/edit", name="edit_security")
     * @Method({"GET", "POST"})
     */
    public function editSecurityAction(Request $request, qvUserPassport $qvUserPassport)
    {
        $deleteForm = $this->createDeleteSecurityForm($qvUserPassport);
        $editForm = $this->createForm('AppBundle\Form\qvUserPassportType', $qvUserPassport);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvUserPassport);
            $em->flush();

            return $this->redirectToRoute('security_list', array('id' => $qvUserPassport->getId()));
        }

        return $this->render('AppBundle:AdminBC:checkpoints_control/security_man/edit.html.twig', array(
            'qvUserPassport' => $qvUserPassport,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a qvUserPassport entity.
     *
     * @Route("/security/{id}/delete", name="delete_security")
     * @Method("DELETE")
     */
    public function deleteSecurityAction(Request $request, qvUserPassport $qvUserPassport)
    {
        $form = $this->createDeleteSecurityForm($qvUserPassport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($qvUserPassport);
            $em->flush();
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
    private function createDeleteSecurityForm(qvUserPassport $qvUserPassport)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('delete_security', array('id' => $qvUserPassport->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
    
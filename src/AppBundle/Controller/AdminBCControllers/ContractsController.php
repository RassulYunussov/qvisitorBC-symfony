<?php

namespace AppBundle\Controller\AdminBCControllers;

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

class ContractsController extends Controller
{
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
     *@Route("/in_contract", name="sectors_in_contract")
     *@Method("GET")
     */

    public function indexSectorsInContractAjaxAction(Request $request)
    {
        if($request->isXmlHttpRequest()) {
            $ContrId = $request->get('id',2);
            $em = $this->getDoctrine()->getManager();
            $qvsectors=$em->getRepository('AppBundle:qvSector')->findSectorsByContract($ContrId);
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
    *@Route("/bycontractlpl", name="contractss-sectors")
    *@Method("GET")
    */
    public function ContractSectorsAjaxAction(Request $request)
    {
        if($request->isXmlHttpRequest()) {
            $item = $request->get('id',1);
            $em = $this->getDoctrine()->getManager();
            $qv = $em->getRepository('AppBundle:qvContract')->findOneById($item);
            $serializer = $this->get('serializer');
            $res = $serializer->serialize($qv, 'json');
            return new Response($res);
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
            
        $contr = $em->getRepository('AppBundle:qvContract')->findOneById($qvContract);
        
        $data = array();

        $editForm = $this->createFormBuilder($contr)
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
}

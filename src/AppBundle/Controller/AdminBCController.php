<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\qvLeaser;
use AppBundle\Entity\qvContract;
use AppBundle\Form\qvContractType;
use AppBundle\Entity\qvFloor;
use AppBundle\Entity\qvSector;
use AppBundle\Entity\qvCheckpoint;
use AppBundle\Entity\qvBuilding;
use AppBundle\Form\leaserType;

/**
 * adminBCController 
 * 
 * @Route("/adminbc")
 * 
 */

class AdminBCController extends Controller
{
 /**
     * @Route("/index")
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
     	
        return $this->render('AppBundle:Adminbc:leasers/leasers.html.twig', array(
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
      /* $username = $request->get('name');
       $user_bin = $request->get('BIN');
*/
        $form = $this->createForm('AppBundle\Form\qvNewLeaser', $qvLeaser);
        $form->handleRequest($request);

     if ($form->isSubmitted() && $form->isValid()) {

           // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvLeaser);
            $em->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user
            return $this->redirectToRoute('leasers_list', array('id' => $qvLeaser->getId()));
        }     
        return $this->render('AppBundle:AdminBC:leasers/createleaser.html.twig', array(
            'qvLeaser' => $qvLeaser,
            'form' => $form->createView()
        ));
    }

    /**
     * Finds and displays a qvLeaser entity.
     *
     * @Route("/leasers/leaser/{id}", name="leasers_show")
     * @Method("GET")
     */
    public function showLeaserAction(qvLeaser $qvLeaser)
    {
        $deleteForm = $this->createDeleteForm($qvLeaser);

        return $this->render('AppBundle:Adminbc:leasers/showleaser.html.twig', array(
            'qvLeaser' => $qvLeaser,
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
        $deleteForm = $this->createDeleteForm($qvLeaser);
        $editForm = $this->createForm('AppBundle\Form\qvLeaserType', $qvLeaser);
        $editForm->handleRequest($request);
		
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvLeaser);
            $em->flush();
			
            return $this->redirectToRoute('leasers_show', array('id' => $qvLeaser->getId()));
        }
        return $this->render('AppBundle:Adminbc:leasers/editleaser.html.twig', array(
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
    public function deleteAction(Request $request, qvLeaser $qvLeaser)
    {
        $form = $this->createDeleteForm($qvLeaser);
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
    private function createDeleteForm(qvLeaser $qvLeaser)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('leasers_delete', array('id' => $qvLeaser->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
	
	
	 /**
     * @Route("/leasers/contracts", name="contracts_index")
     * @Method("GET")
     */
    public function index_contractsAction()
    {
    	$em = $this->getDoctrine()->getManager();

        $qvContracts = $em->getRepository('AppBundle:qvContract')->findAll();

        return $this->render('AppBundle:Adminbc:leasers/contractscontrol.html.twig', array(
            'qvContracts' => $qvContracts,
        ));
    }	
	    
         /**
     * @Route("/leasers/create_new_contract", name="contracts_create")
      * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
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

        return $this->render('AppBundle:Adminbc:leasers/createcontract.html.twig', array(
            'qvContract' => $qvContract,
            'form' => $form->createView(),
        ));
    }


	 /**
     * Finds and displays a qvContract entity.
     *
     * @Route("/leasers/{id}/contract/show", name="contracts_show")
     * @Method("GET")
     */
    public function showAction(qvContract $qvContract)
    {
        $deleteForm = $this->createDeleteContractForm($qvContract);

        return $this->render('AppBundle:Adminbc:leasers/showcontract.html.twig', array(
            'qvContract' => $qvContract,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing qvContract entity.
     *
     * @Route("/leasers/{id}/contract/edit", name="contracts_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, qvContract $qvContract)
    {
        $deleteForm = $this->createDeleteContractForm($qvContract);
        $editForm = $this->createForm('AppBundle\Form\qvContractType', $qvContract);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvContract);
            $em->flush();

            return $this->redirectToRoute('contracts_index', array('id' => $qvContract->getId()));
        }

        return $this->render('AppBundle:Adminbc:leasers/editcontract.html.twig', array(
            'qvContract' => $qvContract,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a qvContract entity.
     *
     * @Route("/leasers/{id}/contract/delete", name="contracts_delete")
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

        return $this->redirectToRoute('contracts_index');
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
 * @Route("/checkpoints_control", name="checkpoint_index")
 * @Method("GET")
 */
    public function checkpoints_controlAction()
    {
        $em = $this->getDoctrine()->getManager();
		
        $qvCheckpoints = $em->getRepository('AppBundle:qvCheckpoint')->findAll();
		
        return $this->render('AppBundle:AdminBC:buildings_control:checkpoints_control/checkpoints_control.html.twig', array(
		'qvCheckpoints' => $qvCheckpoints,
        ));
    }
	
	
	
    /**
     * Creates a new qvCheckpoint entity.
     *
     * @Route("/new", name="checkpoint_new")
     * @Method({"GET", "POST"})
     */
    public function create_checkpointAction(Request $request)
    {
        $qvCheckpoint = new qvCheckpoint();
        $form = $this->createForm('AppBundle\Form\qvCheckpointType', $qvCheckpoint);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvCheckpoint);
            $em->flush();

            return $this->redirectToRoute('checkpoint_show', array('id' => $qvCheckpoint->getId()));
        }

        return $this->render('AppBundle:Adminbc:buildings_control:checkpoints_control/createcheckpoint', array(
            'qvCheckpoint' => $qvCheckpoint,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a qvCheckpoint entity.
     *
     * @Route("/{id}", name="checkpoint_show")
     * @Method("GET")
     */
    public function show_checkpointAction(qvCheckpoint $qvCheckpoint)
    {
	$deleteForm = $this->createDeleteForm($qvCheckpoint);
		
        return $this->render('AppBundle:Adminbc:buildings_control:checkpoints_control/showcheckpoint', array(
		'qvCheckpoint' => $qvCheckpoint,
		'delete_form' => $deleteForm->createView(),
        ));
    }
	
    /**
     * Displays a form to edit an existing qvCheckpoint entity.
		*
     * @Route("/{id}/edit", name="checkpoint_edit")
     * @Method({"GET", "POST"})
     */
    public function edit_checkpointAction(Request $request, qvCheckpoint $qvCheckpoint)
    {
        $deleteForm = $this->createDeleteForm($qvCheckpoint);
        $editForm = $this->createForm('AppBundle\Form\qvCheckpointType', $qvCheckpoint);
        $editForm->handleRequest($request);
		
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvCheckpoint);
            $em->flush();
			
            return $this->redirectToRoute('checkpoint_edit', array('id' => $qvCheckpoint->getId()));
        }
		
        return $this->render('AppBundle:Adminbc:buildings_control:checkpoints_control/editcheckpoint.html.twig', array(
		'qvCheckpoint' => $qvCheckpoint,
		'edit_form' => $editForm->createView(),
		'delete_form' => $deleteForm->createView(),
        ));
    }
	
    /**
     * Deletes a qvCheckpoint entity.
		*
     * @Route("/{id}", name="checkpoint_delete")
     * @Method("DELETE")
     */
    public function delete_checkpointAction(Request $request, qvCheckpoint $qvCheckpoint)
    {
        $form = $this->createDeleteForm($qvCheckpoint);
        $form->handleRequest($request);
		
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($qvCheckpoint);
            $em->flush();
        }
		
        return $this->redirectToRoute('checkpoint_index');
    }
	
    /**
     * Creates a form to delete a qvCheckpoint entity.
		*
     * @param qvCheckpoint $qvCheckpoint The qvCheckpoint entity
		*
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm_checkpoint(qvCheckpoint $qvCheckpoint)
    {
        return $this->createFormBuilder()
		->setAction($this->generateUrl('checkpoint_delete', array('id' => $qvCheckpoint->getId())))
		->setMethod('DELETE')
		->getForm()
        ;
    }
	
		 /**
     * @Route("/buildings_control/show_sector")
     * @Method("GET")
     */
    public function show_sectorAction()
    {
    	
        return $this->render('AppBundle:Adminbc:buildings_control/sectors_control/showsector.html.twig', array(
        		
        ));
    }
	
		 /**
     * @Route("/buildings_control/edit_sector")
     * @Method("GET")
     */
    public function edit_sectorAction()
    {
    	
        return $this->render('AppBundle:Adminbc:buildings_control/sectors_control/editsector.html.twig', array(
        		
        ));
    }
	
		 /**
     * @Route("/buildings_control/create_sector")
     * @Method("GET")
     */
    public function create_sectorAction()
    {
    	
        return $this->render('AppBundle:Adminbc:buildings_control/sectors_control/Createsector.html.twig', array(
        		
        ));
    }
	
		 /**
     * @Route("/buildings_control", name = "buildings_list")
     * @Method("GET")
     */
    public function buildings_controlAction()
    {
        $em = $this->getDoctrine()->getManager();
        $qvBuildings = $em->getRepository('AppBundle:qvBuilding')->findAll();
        
        return $this->render('AppBundle:Adminbc:buildings_control/list_buildings.html.twig', array(
        'qvBuildings' => $qvBuildings
        ));
    }


    /**
     * @Route("/buildings_control/show_building", name = "buildings_show")
     * @Method("GET")
     */
    public function show_buildingAction(qvBuilding $qvBuilding)
    {
         $deleteForm = $this->createDeleteForm($qvBuilding);
       
        return $this->render('AppBundle:Adminbc:buildings_control/show_building.html.twig', array(
            'qvBuilding' => $qvBuilding,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @Route("/buildings_control/edit_building")
     * @Method("GET")
     */
    public function edit_buildingAction()
    {

        return $this->render('AppBundle:Adminbc:buildings_control/edit_building.html.twig', array(

        ));
    }

    /**
     * @Route("/buildings_control/create_building")
     * @Method("GET")
     */
    public function create_buildingsAction()
    {

        return $this->render('AppBundle:Adminbc:buildings_control/create_building.html.twig', array(

        ));
    }

	
		 /**
     * @Route("/buildings_control/edit_floor")
     * @Method("GET")
     */
    public function edit_floorAction()
    {
    	
        return $this->render('AppBundle:Adminbc:buildings_control/floors_control/editfloor.html.twig', array(
        		
        ));
    }
	
		 /**
     * @Route("/buildings_control/show_floor")
     * @Method("GET")
     */
    public function show_floorAction()
    {
    	
        return $this->render('AppBundle:Adminbc:buildings_control/floors_control/showfloor.html.twig', array(
        		
        ));
    }
	
		/**
     * @Route("/buildings_control/create_floor")
     * @Method("GET")
     */
    public function create_floorAction()
    {
    	
        return $this->render('AppBundle:Adminbc:buildings_control/floors_control/createfloor.html.twig', array(
        		
        ));
    }
}

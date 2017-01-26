<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
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
        $deleteForm = $this->createDeleteLeaserForm($qvLeaser);
        $em = $this->getDoctrine()->getManager();
        //$qvContract = $em->getRepository('AppBundle:qvContract')->findAll($leaser);
        return $this->render('AppBundle:Adminbc:leasers/showleaser.html.twig', array(
            'qvLeaser' => $qvLeaser,
          //  'qvContract' => $qvContract,
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
    public function deleteLeaserAction(Request $request, qvLeaser $qvLeaser)
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
    private function createDeleteLeaserForm(qvLeaser $qvLeaser)
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
    public function showContractAction(qvContract $qvContract)
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
    public function editContractAction(Request $request, qvContract $qvContract)
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
 * @Route("/checkpoints_control", name="checkpoints_list")
 * @Method("GET")
 */
    public function checkpoints_controlAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $qvCheckpoints = $em->getRepository('AppBundle:qvCheckpoint')->findAll();
        
        return $this->render('AppBundle:AdminBC:buildings_control/checkpoints_control/checkpointscontrol.html.twig', array(
        'qvCheckpoints' => $qvCheckpoints,
        ));
    }
    
    
    /**
     * Creates a new qvCheckpoint entity.
     *
     * @Route("/create_checkpoint", name="checkpoints_create")
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

            return $this->redirectToRoute('checkpoints_show', array('id' => $qvCheckpoint->getId()));
        }

        return $this->render('AppBundle:Adminbc:buildings_control:checkpoints_control/createcheckpoint', array(
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
    public function show_checkpointAction(qvCheckpoint $qvCheckpoint)
    {
    $deleteForm = $this->createDeleteCheckpointForm($qvCheckpoint);
        
        return $this->render('AppBundle:Adminbc:buildings_control/checkpoints_control/showcheckpoint.html.twig', array(
        'qvCheckpoint' => $qvCheckpoint,
        'delete_form' => $deleteForm->createView(),
        ));
    }
    
    /**
     * Displays a form to edit an existing qvCheckpoint entity.
        *
     * @Route("/checkpoint/{id}/edit", name="checkpoints_edit")
     * @Method({"GET", "POST"})
     */
    public function edit_checkpointAction(Request $request, qvCheckpoint $qvCheckpoint)
    {
        $deleteForm = $this->createDeleteCheckpointForm($qvCheckpoint);
        $editForm = $this->createForm('AppBundle\Form\qvCheckpointType', $qvCheckpoint);
        $editForm->handleRequest($request);
        
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvCheckpoint);
            $em->flush();
            
            return $this->redirectToRoute('checkpoints_edit', array('id' => $qvCheckpoint->getId()));
        }
        
        return $this->render('AppBundle:Adminbc:buildings_control/checkpoints_control/editcheckpoint.html.twig', array(
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
    public function delete_checkpointAction(Request $request, qvCheckpoint $qvCheckpoint)
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
    private function createDeleteCheckpointForm_checkpoint(qvCheckpoint $qvCheckpoint)
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
    public function buildings_controlAction()
    {
        $em = $this->getDoctrine()->getManager();

        $qvBuildings = $em->getRepository('AppBundle:qvBuilding')->findAll();

        return $this->render('AppBundle:AdminBC:buildings_control/list_buildings.html.twig', array(
            'qvBuildings' => $qvBuildings,
        ));
    }

    /**
     * Creates a new qvBuilding entity.
     *
     * @Route("/create_building", name="buildings_create")
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

        return $this->render('AppBundle:AdminBC:buildings_control/create_building.html.twig', array(
            'qvBuilding' => $qvBuilding,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a qvBuilding entity.
     *
     * @Route("/{id}/show", name="buildings_show")
     * @Method("GET")
     */
    public function showBuildingAction(qvBuilding $qvBuilding)
    {
        $deleteForm = $this->createDeleteBuildingForm($qvBuilding);
       $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery(
    'SELECT COUNT(fl) FROM AppBundle:qvFloor fl WHERE fl.building = :name'
)->setParameter('name', $qvBuilding);

$count = $query->getSingleScalarResult();

        //Работает
        $em1 = $this->getDoctrine()->getEntityManager();
        $queryFloor = $em->createQuery(
            'SELECT fl.name from AppBundle:qvFloor fl WHERE fl.building = :name'
            )->setParameter('name', $qvBuilding);
            $floors = $queryFloor->getResult();

    if (!$queryFloor)
    {
    throw $this->CreateNotFoundException('Не найдено информации ни по одному этажу');
    }

else{
        return $this->render('AppBundle:AdminBC:buildings_control/show_building.html.twig', array(
            'qvBuilding' => $qvBuilding,
            'count' => $count,
            'floors' => $floors,
            'delete_form' => $deleteForm->createView(),
        ));
    }
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

        return $this->render('AppBundle:AdminBC:buildings_control/edit_building.html.twig', array(
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
     * @Route("/floors_control", name="floors_index")
     * @Method("GET")
     */
    public function floors_controlAction()
    {
        $em = $this->getDoctrine()->getManager();

        $qvFloors = $em->getRepository('AppBundle:qvFloor')->findAll();

        return $this->render('AppBundle:qvfloor:index.html.twig', array(
            'qvFloors' => $qvFloors,
        ));
    }

    /**
     * Creates a new qvFloor entity.
     *
     * @Route("/new_floor", name="floors_create")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $qvFloor = new qvFloor();
        $form = $this->createForm('AppBundle\Form\qvFloorType', $qvFloor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvFloor);
            $em->flush();

            return $this->redirectToRoute('floors_show', array('id' => $qvFloor->getId()));
        }

        return $this->render('AppBundle:AdminBC/buildings_control/floors_control/createfloor.html.twig', array(
            'qvFloor' => $qvFloor,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a qvFloor entity.
     *
     * @Route("/{id}/show", name="floors_show")
     * @Method("GET")
     */
    public function showAction(qvFloor $qvFloor)
    {
        $deleteForm = $this->createDeleteFloorForm($qvFloor);

        return $this->render('AppBundle:AdminBC/buildings_control/floors_control/showfloor.html.twig', array(
            'qvFloor' => $qvFloor,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing qvFloor entity.
     *
     * @Route("/{id}/edit", name="floors_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, qvFloor $qvFloor)
    {
        $deleteForm = $this->createDeleteFloorForm($qvFloor);
        $editForm = $this->createForm('AppBundle\Form\qvFloorType', $qvFloor);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvFloor);
            $em->flush();

            return $this->redirectToRoute('floors_show', array('id' => $qvFloor->getId()));
        }

        return $this->render('AppBundle:AdminBC/buildings_control/floors_control/editfloor.html.twig', array(
            'qvFloor' => $qvFloor,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a qvFloor entity.
     *
     * @Route("/{id}", name="floors_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, qvFloor $qvFloor)
    {
        $form = $this->createDeleteFloorForm($qvFloor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($qvFloor);
            $em->flush();
        }

        return $this->redirectToRoute('floors_index');
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

}


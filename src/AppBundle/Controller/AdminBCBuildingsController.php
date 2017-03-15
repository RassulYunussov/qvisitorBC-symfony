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
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
 * @Route("/adminbc/buildings")
 * @Security("has_role('ROLE_ADMIN')")
 */

class AdminBCBuildingsController extends Controller
{
	 /**
     * Lists all qvBuilding entities.
     *
     * @Route("/listOfBuildings", name="buildings_list")
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
     * @Route("/create_building", name="buildings_create")
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
     * @Route("/{qvBuilding}/show", name="buildings_show")
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

       $usp = $em->getRepository('AppBundle:qvUserPassport')->findAll();

        return $this->render('AppBundle:AdminBC:buildings_control/buildings/show_building.html.twig', array(
            'qvBuilding' => $qvBuilding,
            'count' => $count,
            'floors' => $floors,
            'check' => $check,
            'usp' => $usp,  
            'delete_form' => $deleteForm->createView(),
        ));
}
    /**
     * Displays a form to edit an existing qvBuilding entity.
     *
     * @Route("/{qvBuilding}/edit", name="buildings_edit")
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
     * @Route("/{id}/delete", name="buildings_delete")
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
     * @Route("/{qvBuilding}/floors_control", name="floors_list")
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
     * @Route("/{qvBuilding}/floors/new", name="floors_create")
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
     * @Route("/{qvBuilding}/floors/{id}/show", name="floors_show")
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
     * @Route("/{qvBuilding}/floors/{id}/edit", name="floors_edit")
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
            return $this->redirectToRoute('buildings_show', array('qvBuilding'=>$qvBuilding->getId()));
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
     * @Route("/{qvBuilding}/floor/{qvFloor}/listOfSectors", name="sectors_list")
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
     * @Route("/{qvBuilding}/floor/{qvFloor}/sectors/new_sector", name="sectors_create")
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
     * @Route("/{qvBuilding}/floor/{qvFloor}/sector/{id}/show", name="sectors_show")
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
     * @Route("/{qvBuilding}/floor/{qvFloor}/sector/{id}/edit", name="sectors_edit")
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
     * @Route("/{qvBuilding}/floor/{qvFloor}/{qvSector}/deleting", name="sector_deleting")
     * @Method({"GET", "POST"})
     */

    public function deleteSectorShowAction(Request $request, qvBuilding $qvBuilding, qvFloor $qvFloor, qvSector $qvSector)
    {
        $em = $this->getDoctrine()->getManager();
        $em->getConnection()->beginTransaction(); 
try {
        $em->remove($qvSector);
        $em->flush();
     	$em->getConnection()->commit();
     	   return $this->redirectToRoute('floors_show', array('qvBuilding'=>$qvBuilding->getId(), 'id'=>$qvFloor->getId()));
     
} catch (Exception $e) {
    $em->getConnection()->rollBack();
    throw $e;
}
}
}

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
 * @Route("/adminbc/checkpoints")
 * @Security("has_role('ROLE_ADMIN')")
 */

class AdminBCCheckpointsController extends Controller
{
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
     * @Route("/in_building_{qvBuilding}/new_checkpoint", name="checkpoints_create")
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
     * @Route("/{id}/in_building_{qvBuilding}/show", name="checkpoints_show")
     * @Method("GET")
     */
    public function showCheckpointAction(qvCheckpoint $qvCheckpoint, qvBuilding $qvBuilding)
    {
    $deleteForm = $this->createDeleteCheckpointForm($qvCheckpoint);
        $currentDate = new \DateTime();
        $roles = $this->getDoctrine()->getManager()->getRepository('AppBundle:qvUser');

           $em = $this->getDoctrine()->getEntityManager();
            $query = $em->createQuery(
                'SELECT count(e) FROM AppBundle:qvEntrance e JOIN e.checkpoint ch WHERE ch.id = :id and (SUBSTRING(e.entrancedate, 0, 12)) = :currentdate'
)->setParameters(array('id'=> $qvCheckpoint, 'currentdate'=>$currentDate->format('Y-m-d')));
$countVisitors = $query->getSingleScalarResult();
      
        return $this->render('AppBundle:AdminBC:checkpoints_control/show_checkpoint.html.twig', array(
        'qvCheckpoint' => $qvCheckpoint,
        'qvBuilding' => $qvBuilding,
        'countVisitors' => $countVisitors,
        'delete_form' => $deleteForm->createView(),
        ));
    }
    
    /**
     * Displays a form to edit an existing qvCheckpoint entity.
     * @ParamConverter("qvBuilding", class="AppBundle:qvBuilding")
     * @Route("/{id}/in_building_{qvBuilding}/edit", name="checkpoints_edit")
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
     * @Route("/{id}/delete", name="checkpoints_delete")
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
}

<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\qvCheckpoint;
use AppBundle\Form\qvCheckpointType;

/**
 * qvCheckpoint controller.
 *
 * @Route("/check")
 */
class qvCheckpointController extends Controller
{
    /**
     * Lists all qvCheckpoint entities.
     *
     * @Route("/", name="checkpoint_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $qvCheckpoints = $em->getRepository('AppBundle:qvCheckpoint')->findAll();

        return $this->render('AppBundle:qvcheckpoint:index.html.twig', array(
            'qvCheckpoints' => $qvCheckpoints,
        ));
    }

    /**
     * Creates a new qvCheckpoint entity.
     *
     * @Route("/new", name="checkpoint_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
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

        return $this->render('AppBundle:qvcheckpoint:new.html.twig', array(
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
    public function showAction(qvCheckpoint $qvCheckpoint)
    {
        $deleteForm = $this->createDeleteForm($qvCheckpoint);

        return $this->render('AppBundle:qvcheckpoint:show.html.twig', array(
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
    public function editAction(Request $request, qvCheckpoint $qvCheckpoint)
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

        return $this->render('AppBundle:qvcheckpoint:edit.html.twig', array(
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
    public function deleteAction(Request $request, qvCheckpoint $qvCheckpoint)
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
    private function createDeleteForm(qvCheckpoint $qvCheckpoint)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('checkpoint_delete', array('id' => $qvCheckpoint->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

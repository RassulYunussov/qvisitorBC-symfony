<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\qvFloor;
use AppBundle\Form\qvFloorType;

/**
 * qvFloor controller.
 *
 * @Route("/floor")
 */
class qvFloorController extends Controller
{
    /**
     * Lists all qvFloor entities.
     *
     * @Route("/", name="floor_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $qvFloors = $em->getRepository('AppBundle:qvFloor')->findAll();

        return $this->render('qvfloor/index.html.twig', array(
            'qvFloors' => $qvFloors,
        ));
    }

    /**
     * Creates a new qvFloor entity.
     *
     * @Route("/new", name="floor_new")
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

            return $this->redirectToRoute('floor_show', array('id' => $qvFloor->getId()));
        }

        return $this->render('qvfloor/new.html.twig', array(
            'qvFloor' => $qvFloor,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a qvFloor entity.
     *
     * @Route("/{id}", name="floor_show")
     * @Method("GET")
     */
    public function showAction(qvFloor $qvFloor)
    {
        $deleteForm = $this->createDeleteForm($qvFloor);

        return $this->render('qvfloor/show.html.twig', array(
            'qvFloor' => $qvFloor,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing qvFloor entity.
     *
     * @Route("/{id}/edit", name="floor_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, qvFloor $qvFloor)
    {
        $deleteForm = $this->createDeleteForm($qvFloor);
        $editForm = $this->createForm('AppBundle\Form\qvFloorType', $qvFloor);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvFloor);
            $em->flush();

            return $this->redirectToRoute('floor_edit', array('id' => $qvFloor->getId()));
        }

        return $this->render('qvfloor/edit.html.twig', array(
            'qvFloor' => $qvFloor,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a qvFloor entity.
     *
     * @Route("/{id}", name="floor_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, qvFloor $qvFloor)
    {
        $form = $this->createDeleteForm($qvFloor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($qvFloor);
            $em->flush();
        }

        return $this->redirectToRoute('floor_index');
    }

    /**
     * Creates a form to delete a qvFloor entity.
     *
     * @param qvFloor $qvFloor The qvFloor entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(qvFloor $qvFloor)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('floor_delete', array('id' => $qvFloor->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

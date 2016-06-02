<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\qvBuilding;
use AppBundle\Form\qvBuildingType;

/**
 * qvBuilding controller.
 *
 * @Route("/building")
 */
class qvBuildingController extends Controller
{
    /**
     * Lists all qvBuilding entities.
     *
     * @Route("/", name="building_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $qvBuildings = $em->getRepository('AppBundle:qvBuilding')->findAll();

        return $this->render('AppBundle:qvbuilding:index.html.twig', array(
            'qvBuildings' => $qvBuildings,
        ));
    }

    /**
     * Creates a new qvBuilding entity.
     *
     * @Route("/new", name="building_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $qvBuilding = new qvBuilding();
        $form = $this->createForm('AppBundle\Form\qvBuildingType', $qvBuilding);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvBuilding);
            $em->flush();

            return $this->redirectToRoute('building_show', array('id' => $qvBuilding->getId()));
        }

        return $this->render('AppBundle:qvbuilding:new.html.twig', array(
            'qvBuilding' => $qvBuilding,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a qvBuilding entity.
     *
     * @Route("/{id}", name="building_show")
     * @Method("GET")
     */
    public function showAction(qvBuilding $qvBuilding)
    {
        $deleteForm = $this->createDeleteForm($qvBuilding);

        return $this->render('AppBundle:qvbuilding:show.html.twig', array(
            'qvBuilding' => $qvBuilding,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing qvBuilding entity.
     *
     * @Route("/{id}/edit", name="building_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, qvBuilding $qvBuilding)
    {
        $deleteForm = $this->createDeleteForm($qvBuilding);
        $editForm = $this->createForm('AppBundle\Form\qvBuildingType', $qvBuilding);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvBuilding);
            $em->flush();

            return $this->redirectToRoute('building_edit', array('id' => $qvBuilding->getId()));
        }

        return $this->render('AppBundle:qvbuilding:edit.html.twig', array(
            'qvBuilding' => $qvBuilding,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a qvBuilding entity.
     *
     * @Route("/{id}", name="building_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, qvBuilding $qvBuilding)
    {
        $form = $this->createDeleteForm($qvBuilding);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($qvBuilding);
            $em->flush();
        }

        return $this->redirectToRoute('building_index');
    }

    /**
     * Creates a form to delete a qvBuilding entity.
     *
     * @param qvBuilding $qvBuilding The qvBuilding entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(qvBuilding $qvBuilding)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('building_delete', array('id' => $qvBuilding->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

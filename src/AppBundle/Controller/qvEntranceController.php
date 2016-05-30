<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\qvEntrance;
use AppBundle\Form\qvEntranceType;

/**
 * qvEntrance controller.
 *
 * @Route("/entrance")
 */
class qvEntranceController extends Controller
{
    /**
     * Lists all qvEntrance entities.
     *
     * @Route("/", name="entrance_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $qvEntrances = $em->getRepository('AppBundle:qvEntrance')->findAll();

        return $this->render('qventrance/index.html.twig', array(
            'qvEntrances' => $qvEntrances,
        ));
    }

    /**
     * Creates a new qvEntrance entity.
     *
     * @Route("/new", name="entrance_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $qvEntrance = new qvEntrance();
        $form = $this->createForm('AppBundle\Form\qvEntranceType', $qvEntrance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvEntrance);
            $em->flush();

            return $this->redirectToRoute('entrance_show', array('id' => $qvEntrance->getId()));
        }

        return $this->render('qventrance/new.html.twig', array(
            'qvEntrance' => $qvEntrance,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a qvEntrance entity.
     *
     * @Route("/{id}", name="entrance_show")
     * @Method("GET")
     */
    public function showAction(qvEntrance $qvEntrance)
    {
        $deleteForm = $this->createDeleteForm($qvEntrance);

        return $this->render('qventrance/show.html.twig', array(
            'qvEntrance' => $qvEntrance,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing qvEntrance entity.
     *
     * @Route("/{id}/edit", name="entrance_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, qvEntrance $qvEntrance)
    {
        $deleteForm = $this->createDeleteForm($qvEntrance);
        $editForm = $this->createForm('AppBundle\Form\qvEntranceType', $qvEntrance);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvEntrance);
            $em->flush();

            return $this->redirectToRoute('entrance_edit', array('id' => $qvEntrance->getId()));
        }

        return $this->render('qventrance/edit.html.twig', array(
            'qvEntrance' => $qvEntrance,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a qvEntrance entity.
     *
     * @Route("/{id}", name="entrance_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, qvEntrance $qvEntrance)
    {
        $form = $this->createDeleteForm($qvEntrance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($qvEntrance);
            $em->flush();
        }

        return $this->redirectToRoute('entrance_index');
    }

    /**
     * Creates a form to delete a qvEntrance entity.
     *
     * @param qvEntrance $qvEntrance The qvEntrance entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(qvEntrance $qvEntrance)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('entrance_delete', array('id' => $qvEntrance->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

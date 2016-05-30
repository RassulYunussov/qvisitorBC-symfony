<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\qvLeaser;
use AppBundle\Form\qvLeaserType;

/**
 * qvLeaser controller.
 *
 * @Route("/leaser")
 */
class qvLeaserController extends Controller
{
    /**
     * Lists all qvLeaser entities.
     *
     * @Route("/", name="leaser_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $qvLeasers = $em->getRepository('AppBundle:qvLeaser')->findAll();

        return $this->render('qvleaser/index.html.twig', array(
            'qvLeasers' => $qvLeasers,
        ));
    }

    /**
     * Creates a new qvLeaser entity.
     *
     * @Route("/new", name="leaser_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $qvLeaser = new qvLeaser();
        $form = $this->createForm('AppBundle\Form\qvLeaserType', $qvLeaser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvLeaser);
            $em->flush();

            return $this->redirectToRoute('leaser_show', array('id' => $qvLeaser->getId()));
        }

        return $this->render('qvleaser/new.html.twig', array(
            'qvLeaser' => $qvLeaser,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a qvLeaser entity.
     *
     * @Route("/{id}", name="leaser_show")
     * @Method("GET")
     */
    public function showAction(qvLeaser $qvLeaser)
    {
        $deleteForm = $this->createDeleteForm($qvLeaser);

        return $this->render('qvleaser/show.html.twig', array(
            'qvLeaser' => $qvLeaser,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing qvLeaser entity.
     *
     * @Route("/{id}/edit", name="leaser_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, qvLeaser $qvLeaser)
    {
        $deleteForm = $this->createDeleteForm($qvLeaser);
        $editForm = $this->createForm('AppBundle\Form\qvLeaserType', $qvLeaser);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvLeaser);
            $em->flush();

            return $this->redirectToRoute('leaser_edit', array('id' => $qvLeaser->getId()));
        }

        return $this->render('qvleaser/edit.html.twig', array(
            'qvLeaser' => $qvLeaser,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a qvLeaser entity.
     *
     * @Route("/{id}", name="leaser_delete")
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

        return $this->redirectToRoute('leaser_index');
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
            ->setAction($this->generateUrl('leaser_delete', array('id' => $qvLeaser->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

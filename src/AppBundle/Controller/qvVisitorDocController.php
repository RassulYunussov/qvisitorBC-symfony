<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\qvVisitorDoc;
use AppBundle\Form\qvVisitorDocType;

/**
 * qvVisitorDoc controller.
 *
 * @Route("/visitordoc")
 */
class qvVisitorDocController extends Controller
{
    /**
     * Lists all qvVisitorDoc entities.
     *
     * @Route("/", name="visitordoc_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $qvVisitorDocs = $em->getRepository('AppBundle:qvVisitorDoc')->findAll();

        return $this->render('qvvisitordoc/index.html.twig', array(
            'qvVisitorDocs' => $qvVisitorDocs,
        ));
    }

    /**
     * Creates a new qvVisitorDoc entity.
     *
     * @Route("/new", name="visitordoc_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $qvVisitorDoc = new qvVisitorDoc();
        $form = $this->createForm('AppBundle\Form\qvVisitorDocType', $qvVisitorDoc);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvVisitorDoc);
            $em->flush();

            return $this->redirectToRoute('visitordoc_show', array('id' => $qvVisitorDoc->getId()));
        }

        return $this->render('qvvisitordoc/new.html.twig', array(
            'qvVisitorDoc' => $qvVisitorDoc,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a qvVisitorDoc entity.
     *
     * @Route("/{id}", name="visitordoc_show")
     * @Method("GET")
     */
    public function showAction(qvVisitorDoc $qvVisitorDoc)
    {
        $deleteForm = $this->createDeleteForm($qvVisitorDoc);

        return $this->render('qvvisitordoc/show.html.twig', array(
            'qvVisitorDoc' => $qvVisitorDoc,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing qvVisitorDoc entity.
     *
     * @Route("/{id}/edit", name="visitordoc_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, qvVisitorDoc $qvVisitorDoc)
    {
        $deleteForm = $this->createDeleteForm($qvVisitorDoc);
        $editForm = $this->createForm('AppBundle\Form\qvVisitorDocType', $qvVisitorDoc);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvVisitorDoc);
            $em->flush();

            return $this->redirectToRoute('visitordoc_edit', array('id' => $qvVisitorDoc->getId()));
        }

        return $this->render('qvvisitordoc/edit.html.twig', array(
            'qvVisitorDoc' => $qvVisitorDoc,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a qvVisitorDoc entity.
     *
     * @Route("/{id}", name="visitordoc_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, qvVisitorDoc $qvVisitorDoc)
    {
        $form = $this->createDeleteForm($qvVisitorDoc);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($qvVisitorDoc);
            $em->flush();
        }

        return $this->redirectToRoute('visitordoc_index');
    }

    /**
     * Creates a form to delete a qvVisitorDoc entity.
     *
     * @param qvVisitorDoc $qvVisitorDoc The qvVisitorDoc entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(qvVisitorDoc $qvVisitorDoc)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('visitordoc_delete', array('id' => $qvVisitorDoc->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

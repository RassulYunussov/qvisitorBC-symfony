<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\qvVisitor;
use AppBundle\Form\qvVisitorType;

/**
 * qvVisitor controller.
 *
 * @Route("/visitor")
 */
class qvVisitorController extends Controller
{
    /**
     * Lists all qvVisitor entities.
     *
     * @Route("/", name="visitor_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $qvVisitors = $em->getRepository('AppBundle:qvVisitor')->findAll();

        return $this->render('qvvisitor/index.html.twig', array(
            'qvVisitors' => $qvVisitors,
        ));
    }

    /**
     * Creates a new qvVisitor entity.
     *
     * @Route("/new", name="visitor_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $qvVisitor = new qvVisitor();
        $form = $this->createForm('AppBundle\Form\qvVisitorType', $qvVisitor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvVisitor);
            $em->flush();

            return $this->redirectToRoute('visitor_show', array('id' => $qvVisitor->getId()));
        }

        return $this->render('qvvisitor/new.html.twig', array(
            'qvVisitor' => $qvVisitor,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a qvVisitor entity.
     *
     * @Route("/{id}", name="visitor_show")
     * @Method("GET")
     */
    public function showAction(qvVisitor $qvVisitor)
    {
        $deleteForm = $this->createDeleteForm($qvVisitor);

        return $this->render('qvvisitor/show.html.twig', array(
            'qvVisitor' => $qvVisitor,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing qvVisitor entity.
     *
     * @Route("/{id}/edit", name="visitor_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, qvVisitor $qvVisitor)
    {
        $deleteForm = $this->createDeleteForm($qvVisitor);
        $editForm = $this->createForm('AppBundle\Form\qvVisitorType', $qvVisitor);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvVisitor);
            $em->flush();

            return $this->redirectToRoute('visitor_edit', array('id' => $qvVisitor->getId()));
        }

        return $this->render('qvvisitor/edit.html.twig', array(
            'qvVisitor' => $qvVisitor,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a qvVisitor entity.
     *
     * @Route("/{id}", name="visitor_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, qvVisitor $qvVisitor)
    {
        $form = $this->createDeleteForm($qvVisitor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($qvVisitor);
            $em->flush();
        }

        return $this->redirectToRoute('visitor_index');
    }

    /**
     * Creates a form to delete a qvVisitor entity.
     *
     * @param qvVisitor $qvVisitor The qvVisitor entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(qvVisitor $qvVisitor)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('visitor_delete', array('id' => $qvVisitor->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\qvOrderType;
use AppBundle\Form\qvOrderTypeType;

/**
 * qvOrderType controller.
 *
 * @Route("/ordertype")
 */
class qvOrderTypeController extends Controller
{
    /**
     * Lists all qvOrderType entities.
     *
     * @Route("/", name="qvordertype_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $qvOrderTypes = $em->getRepository('AppBundle:qvOrderType')->findAll();

        return $this->render('AppBundle:qvordertype:index.html.twig', array(
            'qvOrderTypes' => $qvOrderTypes,
        ));
    }

    /**
     * Creates a new qvOrderType entity.
     *
     * @Route("/new", name="qvordertype_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $qvOrderType = new qvOrderType();
        $form = $this->createForm('AppBundle\Form\qvOrderTypeType', $qvOrderType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvOrderType);
            $em->flush();

            return $this->redirectToRoute('qvordertype_show', array('id' => $qvOrderType->getId()));
        }

        return $this->render('AppBundle:qvordertype:new.html.twig', array(
            'qvOrderType' => $qvOrderType,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a qvOrderType entity.
     *
     * @Route("/{id}", name="qvordertype_show")
     * @Method("GET")
     */
    public function showAction(qvOrderType $qvOrderType)
    {
        $deleteForm = $this->createDeleteForm($qvOrderType);

        return $this->render('AppBundle:qvordertype:show.html.twig', array(
            'qvOrderType' => $qvOrderType,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing qvOrderType entity.
     *
     * @Route("/{id}/edit", name="qvordertype_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, qvOrderType $qvOrderType)
    {
        $deleteForm = $this->createDeleteForm($qvOrderType);
        $editForm = $this->createForm('AppBundle\Form\qvOrderTypeType', $qvOrderType);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvOrderType);
            $em->flush();

            return $this->redirectToRoute('qvordertype_edit', array('id' => $qvOrderType->getId()));
        }

        return $this->render('AppBundle:qvordertype:edit.html.twig', array(
            'qvOrderType' => $qvOrderType,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a qvOrderType entity.
     *
     * @Route("/{id}", name="qvordertype_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, qvOrderType $qvOrderType)
    {
        $form = $this->createDeleteForm($qvOrderType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($qvOrderType);
            $em->flush();
        }

        return $this->redirectToRoute('qvordertype_index');
    }

    /**
     * Creates a form to delete a qvOrderType entity.
     *
     * @param qvOrderType $qvOrderType The qvOrderType entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(qvOrderType $qvOrderType)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('qvordertype_delete', array('id' => $qvOrderType->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

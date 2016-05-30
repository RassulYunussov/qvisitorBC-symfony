<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\qvOrder;
use AppBundle\Form\qvOrderType;

/**
 * qvOrder controller.
 *
 * @Route("/order")
 */
class qvOrderController extends Controller
{
    /**
     * Lists all qvOrder entities.
     *
     * @Route("/", name="order_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $qvOrders = $em->getRepository('AppBundle:qvOrder')->findAll();

        return $this->render('qvorder/index.html.twig', array(
            'qvOrders' => $qvOrders,
        ));
    }

    /**
     * Creates a new qvOrder entity.
     *
     * @Route("/new", name="order_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $qvOrder = new qvOrder();
        $form = $this->createForm('AppBundle\Form\qvOrderType', $qvOrder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvOrder);
            $em->flush();

            return $this->redirectToRoute('order_show', array('id' => $qvOrder->getId()));
        }

        return $this->render('qvorder/new.html.twig', array(
            'qvOrder' => $qvOrder,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a qvOrder entity.
     *
     * @Route("/{id}", name="order_show")
     * @Method("GET")
     */
    public function showAction(qvOrder $qvOrder)
    {
        $deleteForm = $this->createDeleteForm($qvOrder);

        return $this->render('qvorder/show.html.twig', array(
            'qvOrder' => $qvOrder,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing qvOrder entity.
     *
     * @Route("/{id}/edit", name="order_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, qvOrder $qvOrder)
    {
        $deleteForm = $this->createDeleteForm($qvOrder);
        $editForm = $this->createForm('AppBundle\Form\qvOrderType', $qvOrder);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvOrder);
            $em->flush();

            return $this->redirectToRoute('order_edit', array('id' => $qvOrder->getId()));
        }

        return $this->render('qvorder/edit.html.twig', array(
            'qvOrder' => $qvOrder,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a qvOrder entity.
     *
     * @Route("/{id}", name="order_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, qvOrder $qvOrder)
    {
        $form = $this->createDeleteForm($qvOrder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($qvOrder);
            $em->flush();
        }

        return $this->redirectToRoute('order_index');
    }

    /**
     * Creates a form to delete a qvOrder entity.
     *
     * @param qvOrder $qvOrder The qvOrder entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(qvOrder $qvOrder)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('order_delete', array('id' => $qvOrder->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

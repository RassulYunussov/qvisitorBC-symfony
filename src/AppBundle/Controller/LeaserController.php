<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\qvOrder;
use AppBundle\Entity\qvLeaser;
use AppBundle\Entity\qvUser;
use AppBundle\Entity\qvUserPassport;
use AppBundle\Entity\qvContract;


/**
 * @Route("/leaser")
 */

class LeaserController extends Controller
{
	
    /**
     * @Route("/index", name="index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $em1 = $this->getDoctrine()->getEntityManager();
    	$user = $this->get('security.token_storage')->getToken()->getUser();
        $userPassport=$em->getRepository('AppBundle:qvUserPassport')->findOneBy(array('user'=>$user->getId()));
        $qvContracts = $em->getRepository('AppBundle:qvContract')->findBy(array('leaser'=>$user->getLeaser()));
        $count = $em1->createQuery('SELECT count(contract) from AppBundle:qvContract contract where contract.leaser = :leaser')->setParameter('leaser',$user->getLeaser())->getSingleScalarResult();
        return $this->render('AppBundle:Leaser:index.html.twig', array(
        	'userPassport'=>$userPassport,
            'qvContracts'=>$qvContracts,
            'count' => $count
        ));
    }
    
	 /**
     * @Route("/orders", name="show_orders")
     * @Method("GET")
     */
    public function showOrdersAction()
    {
    	$em = $this->getDoctrine()->getManager();
        $qvOrders=$em->getRepository('AppBundle:qvOrder')->findAll();
        return $this->render('AppBundle:Leaser:orders_list.html.twig', array(
        		'qvOrders'=>$qvOrders,
        ));
    }

    /**
     * @Route("/orders/create-order", name="create_order")
     * @Method({"GET", "POST"})
     */
    public function createOrderAction(Request $request)
    {

        $qvOrder = new qvOrder();
        $form = $this->createForm('AppBundle\Form\qvOrderType', $qvOrder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvOrder);
            $em->flush();

            return $this->redirectToRoute('show_orders');
        }

        return $this->render('AppBundle:Leaser:create_order.html.twig', array(
            'qvOrder' => $qvOrder,
            'form' => $form->createView(),
        ));
    }
    
    /**
     * @Route("/orders/{id}/edit-order", name="edit_order")
     * @Method({"GET", "POST"})
     */
    public function editOrderAction(Request $request, qvOrder $qvOrder)
    {
        $deleteForm = $this->createDeleteForm($qvOrder);
        $editForm = $this->createForm('AppBundle\Form\qvOrderType', $qvOrder);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvOrder);
            $em->flush();

            return $this->redirectToRoute('edit_order', array('id' => $qvOrder->getId()));
        }

        return $this->render('AppBundle:Leaser:edit_order.html.twig', array(
            'qvOrder' => $qvOrder,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * Deletes a qvOrder entity.
     *
     * @Route("/orders/{id}/delete", name="order_delete")
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

        return $this->redirectToRoute('show_orders');
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

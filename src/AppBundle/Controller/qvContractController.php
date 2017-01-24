<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\qvContract;
use AppBundle\Form\qvContractType;

/**
 * qvContract controller.
 *
 * @Route("/contract")
 */
class qvContractController extends Controller
{
    /**
     * Lists all qvContract entities.
     *
     * @Route("/", name="contract_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $qvContracts = $em->getRepository('AppBundle:qvContract')->findAll();

        return $this->render('AppBundle:qvcontract:index.html.twig', array(
            'qvContracts' => $qvContracts,
        ));
    }

    /**
     * Creates a new qvContract entity.
     *
     * @Route("/new", name="contract_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $qvContract = new qvContract();
        $form = $this->createForm('AppBundle\Form\qvContractType', $qvContract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvContract);
            $em->flush();

            return $this->redirectToRoute('contract_show', array('id' => $qvContract->getId()));
        }

        return $this->render('AppBundle:qvcontract:new.html.twig', array(
            'qvContract' => $qvContract,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a qvContract entity.
     *
     * @Route("/{id}/show", name="contract_show")
     * @Method("GET")
     */
    public function showAction(qvContract $qvContract)
    {
        $deleteForm = $this->createDeleteForm($qvContract);

        return $this->render('AppBundle:qvcontract:show.html.twig', array(
            'qvContract' => $qvContract,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing qvContract entity.
     *
     * @Route("/{id}/edit", name="contract_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, qvContract $qvContract)
    {
        $deleteForm = $this->createDeleteForm($qvContract);
        $editForm = $this->createForm('AppBundle\Form\qvContractType', $qvContract);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvContract);
            $em->flush();

            return $this->redirectToRoute('contract_edit', array('id' => $qvContract->getId()));
        }

        return $this->render('AppBundle:qvcontract:edit.html.twig', array(
            'qvContract' => $qvContract,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a qvContract entity.
     *
     * @Route("/{id}", name="contract_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, qvContract $qvContract)
    {
        $form = $this->createDeleteForm($qvContract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($qvContract);
            $em->flush();
        }

        return $this->redirectToRoute('contract_index');
    }

    /**
     * Creates a form to delete a qvContract entity.
     *
     * @param qvContract $qvContract The qvContract entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(qvContract $qvContract)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('contract_delete', array('id' => $qvContract->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

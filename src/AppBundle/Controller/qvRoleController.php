<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\qvRole;
use AppBundle\Form\qvRoleType;

/**
 * qvRole controller.
 *
 * @Route("/role")
 */
class qvRoleController extends Controller
{
    /**
     * Lists all qvRole entities.
     *
     * @Route("/", name="role_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $qvRoles = $em->getRepository('AppBundle:qvRole')->findAll();

        return $this->render('qvrole/index.html.twig', array(
            'qvRoles' => $qvRoles,
        ));
    }

    /**
     * Creates a new qvRole entity.
     *
     * @Route("/new", name="role_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $qvRole = new qvRole();
        $form = $this->createForm('AppBundle\Form\qvRoleType', $qvRole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvRole);
            $em->flush();

            return $this->redirectToRoute('role_show', array('id' => $qvRole->getId()));
        }

        return $this->render('qvrole/new.html.twig', array(
            'qvRole' => $qvRole,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a qvRole entity.
     *
     * @Route("/{id}", name="role_show")
     * @Method("GET")
     */
    public function showAction(qvRole $qvRole)
    {
        $deleteForm = $this->createDeleteForm($qvRole);

        return $this->render('qvrole/show.html.twig', array(
            'qvRole' => $qvRole,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing qvRole entity.
     *
     * @Route("/{id}/edit", name="role_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, qvRole $qvRole)
    {
        $deleteForm = $this->createDeleteForm($qvRole);
        $editForm = $this->createForm('AppBundle\Form\qvRoleType', $qvRole);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvRole);
            $em->flush();

            return $this->redirectToRoute('role_edit', array('id' => $qvRole->getId()));
        }

        return $this->render('qvrole/edit.html.twig', array(
            'qvRole' => $qvRole,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a qvRole entity.
     *
     * @Route("/{id}", name="role_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, qvRole $qvRole)
    {
        $form = $this->createDeleteForm($qvRole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($qvRole);
            $em->flush();
        }

        return $this->redirectToRoute('role_index');
    }

    /**
     * Creates a form to delete a qvRole entity.
     *
     * @param qvRole $qvRole The qvRole entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(qvRole $qvRole)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('role_delete', array('id' => $qvRole->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

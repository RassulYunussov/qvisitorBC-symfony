<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\qvGender;
use AppBundle\Form\qvGenderType;

/**
 * qvGender controller.
 *
 * @Route("/gender")
 */
class qvGenderController extends Controller
{
    /**
     * Lists all qvGender entities.
     *
     * @Route("/", name="gender_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $qvGenders = $em->getRepository('AppBundle:qvGender')->findAll();

        return $this->render('AppBundle:qvgender:index.html.twig', array(
            'qvGenders' => $qvGenders,
        ));
    }

    /**
     * Creates a new qvGender entity.
     *
     * @Route("/new", name="gender_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $qvGender = new qvGender();
        $form = $this->createForm('AppBundle\Form\qvGenderType', $qvGender);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvGender);
            $em->flush();

            return $this->redirectToRoute('gender_show', array('id' => $qvGender->getId()));
        }

        return $this->render('AppBundle:qvgender:new.html.twig', array(
            'qvGender' => $qvGender,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a qvGender entity.
     *
     * @Route("/{id}", name="gender_show")
     * @Method("GET")
     */
    public function showAction(qvGender $qvGender)
    {
        $deleteForm = $this->createDeleteForm($qvGender);

        return $this->render('AppBundle:qvgender:show.html.twig', array(
            'qvGender' => $qvGender,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing qvGender entity.
     *
     * @Route("/{id}/edit", name="gender_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, qvGender $qvGender)
    {
        $deleteForm = $this->createDeleteForm($qvGender);
        $editForm = $this->createForm('AppBundle\Form\qvGenderType', $qvGender);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvGender);
            $em->flush();

            return $this->redirectToRoute('gender_edit', array('id' => $qvGender->getId()));
        }

        return $this->render('AppBundle:qvgender:edit.html.twig', array(
            'qvGender' => $qvGender,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a qvGender entity.
     *
     * @Route("/{id}", name="gender_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, qvGender $qvGender)
    {
        $form = $this->createDeleteForm($qvGender);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($qvGender);
            $em->flush();
        }

        return $this->redirectToRoute('gender_index');
    }

    /**
     * Creates a form to delete a qvGender entity.
     *
     * @param qvGender $qvGender The qvGender entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(qvGender $qvGender)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('gender_delete', array('id' => $qvGender->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\qvUserPassport;
use AppBundle\Form\qvUserPassportType;

/**
 * qvUserPassport controller.
 *
 * @Route("/userpassport")
 */
class qvUserPassportController extends Controller
{
    /**
     * Lists all qvUserPassport entities.
     *
     * @Route("/", name="userpassport_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $qvUserPassports = $em->getRepository('AppBundle:qvUserPassport')->findAll();

        return $this->render('AppBundle:qvuserpassport:index.html.twig', array(
            'qvUserPassports' => $qvUserPassports,
        ));
    }

    /**
     * Creates a new qvUserPassport entity.
     *
     * @Route("/new", name="userpassport_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $qvUserPassport = new qvUserPassport();
        $form = $this->createForm('AppBundle\Form\qvUserPassportType', $qvUserPassport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvUserPassport);
            $em->flush();

            return $this->redirectToRoute('userpassport_show', array('id' => $qvUserPassport->getId()));
        }

        return $this->render('AppBundle:qvuserpassport:new.html.twig', array(
            'qvUserPassport' => $qvUserPassport,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a qvUserPassport entity.
     *
     * @Route("/{id}", name="userpassport_show")
     * @Method("GET")
     */
    public function showAction(qvUserPassport $qvUserPassport)
    {
        $deleteForm = $this->createDeleteForm($qvUserPassport);

        return $this->render('AppBundle:qvuserpassport:show.html.twig', array(
            'qvUserPassport' => $qvUserPassport,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing qvUserPassport entity.
     *
     * @Route("/{id}/edit", name="userpassport_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, qvUserPassport $qvUserPassport)
    {
        $deleteForm = $this->createDeleteForm($qvUserPassport);
        $editForm = $this->createForm('AppBundle\Form\qvUserPassportType', $qvUserPassport);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvUserPassport);
            $em->flush();

            return $this->redirectToRoute('userpassport_edit', array('id' => $qvUserPassport->getId()));
        }

        return $this->render('AppBundle:qvuserpassport:edit.html.twig', array(
            'qvUserPassport' => $qvUserPassport,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a qvUserPassport entity.
     *
     * @Route("/{id}", name="userpassport_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, qvUserPassport $qvUserPassport)
    {
        $form = $this->createDeleteForm($qvUserPassport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($qvUserPassport);
            $em->flush();
        }

        return $this->redirectToRoute('userpassport_index');
    }

    /**
     * Creates a form to delete a qvUserPassport entity.
     *
     * @param qvUserPassport $qvUserPassport The qvUserPassport entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(qvUserPassport $qvUserPassport)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('userpassport_delete', array('id' => $qvUserPassport->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

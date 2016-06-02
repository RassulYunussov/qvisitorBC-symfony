<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\qvVisitorPhoto;
use AppBundle\Form\qvVisitorPhotoType;

/**
 * qvVisitorPhoto controller.
 *
 * @Route("/visitorphoto")
 */
class qvVisitorPhotoController extends Controller
{
    /**
     * Lists all qvVisitorPhoto entities.
     *
     * @Route("/", name="visitorphoto_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $qvVisitorPhotos = $em->getRepository('AppBundle:qvVisitorPhoto')->findAll();

        return $this->render('AppBundle:qvvisitorphoto:index.html.twig', array(
            'qvVisitorPhotos' => $qvVisitorPhotos,
        ));
    }

    /**
     * Creates a new qvVisitorPhoto entity.
     *
     * @Route("/new", name="visitorphoto_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $qvVisitorPhoto = new qvVisitorPhoto();
        $form = $this->createForm('AppBundle\Form\qvVisitorPhotoType', $qvVisitorPhoto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvVisitorPhoto);
            $em->flush();

            return $this->redirectToRoute('visitorphoto_show', array('id' => $qvVisitorPhoto->getId()));
        }

        return $this->render('AppBundle:qvvisitorphoto:new.html.twig', array(
            'qvVisitorPhoto' => $qvVisitorPhoto,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a qvVisitorPhoto entity.
     *
     * @Route("/{id}", name="visitorphoto_show")
     * @Method("GET")
     */
    public function showAction(qvVisitorPhoto $qvVisitorPhoto)
    {
        $deleteForm = $this->createDeleteForm($qvVisitorPhoto);

        return $this->render('AppBundle:qvvisitorphoto:show.html.twig', array(
            'qvVisitorPhoto' => $qvVisitorPhoto,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing qvVisitorPhoto entity.
     *
     * @Route("/{id}/edit", name="visitorphoto_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, qvVisitorPhoto $qvVisitorPhoto)
    {
        $deleteForm = $this->createDeleteForm($qvVisitorPhoto);
        $editForm = $this->createForm('AppBundle\Form\qvVisitorPhotoType', $qvVisitorPhoto);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvVisitorPhoto);
            $em->flush();

            return $this->redirectToRoute('visitorphoto_edit', array('id' => $qvVisitorPhoto->getId()));
        }

        return $this->render('AppBundle:qvvisitorphoto:edit.html.twig', array(
            'qvVisitorPhoto' => $qvVisitorPhoto,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a qvVisitorPhoto entity.
     *
     * @Route("/{id}", name="visitorphoto_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, qvVisitorPhoto $qvVisitorPhoto)
    {
        $form = $this->createDeleteForm($qvVisitorPhoto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($qvVisitorPhoto);
            $em->flush();
        }

        return $this->redirectToRoute('visitorphoto_index');
    }

    /**
     * Creates a form to delete a qvVisitorPhoto entity.
     *
     * @param qvVisitorPhoto $qvVisitorPhoto The qvVisitorPhoto entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(qvVisitorPhoto $qvVisitorPhoto)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('visitorphoto_delete', array('id' => $qvVisitorPhoto->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

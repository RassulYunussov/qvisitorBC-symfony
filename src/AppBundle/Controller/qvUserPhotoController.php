<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\qvUserPhoto;
use AppBundle\Form\qvUserPhotoType;

/**
 * qvUserPhoto controller.
 *
 * @Route("/userphoto")
 */
class qvUserPhotoController extends Controller
{
    /**
     * Lists all qvUserPhoto entities.
     *
     * @Route("/", name="userphoto_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $qvUserPhotos = $em->getRepository('AppBundle:qvUserPhoto')->findAll();

        return $this->render('AppBundle:qvuserphoto:index.html.twig', array(
            'qvUserPhotos' => $qvUserPhotos,
        ));
    }

    /**
     * Creates a new qvUserPhoto entity.
     *
     * @Route("/new", name="userphoto_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $qvUserPhoto = new qvUserPhoto();
        $form = $this->createForm('AppBundle\Form\qvUserPhotoType', $qvUserPhoto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvUserPhoto);
            $em->flush();

            return $this->redirectToRoute('userphoto_show', array('id' => $qvUserPhoto->getId()));
        }

        return $this->render('AppBundle:qvuserphoto:new.html.twig', array(
            'qvUserPhoto' => $qvUserPhoto,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a qvUserPhoto entity.
     *
     * @Route("/{id}", name="userphoto_show")
     * @Method("GET")
     */
    public function showAction(qvUserPhoto $qvUserPhoto)
    {
        $deleteForm = $this->createDeleteForm($qvUserPhoto);

        return $this->render('AppBundle:qvuserphoto:show.html.twig', array(
            'qvUserPhoto' => $qvUserPhoto,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing qvUserPhoto entity.
     *
     * @Route("/{id}/edit", name="userphoto_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, qvUserPhoto $qvUserPhoto)
    {
        $deleteForm = $this->createDeleteForm($qvUserPhoto);
        $editForm = $this->createForm('AppBundle\Form\qvUserPhotoType', $qvUserPhoto);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvUserPhoto);
            $em->flush();

            return $this->redirectToRoute('userphoto_edit', array('id' => $qvUserPhoto->getId()));
        }

        return $this->render('AppBundle:qvuserphoto:edit.html.twig', array(
            'qvUserPhoto' => $qvUserPhoto,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a qvUserPhoto entity.
     *
     * @Route("/{id}", name="userphoto_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, qvUserPhoto $qvUserPhoto)
    {
        $form = $this->createDeleteForm($qvUserPhoto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($qvUserPhoto);
            $em->flush();
        }

        return $this->redirectToRoute('userphoto_index');
    }

    /**
     * Creates a form to delete a qvUserPhoto entity.
     *
     * @param qvUserPhoto $qvUserPhoto The qvUserPhoto entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(qvUserPhoto $qvUserPhoto)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('userphoto_delete', array('id' => $qvUserPhoto->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

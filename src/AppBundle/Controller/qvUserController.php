<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\qvUser;
use AppBundle\Form\qvUserType;

/**
 * qvUser controller.
 *
 * @Route("/user")
 */
class qvUserController extends Controller
{
    /**
     * Lists all qvUser entities.
     *
     * @Route("/", name="user_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $qvUsers = $em->getRepository('AppBundle:qvUser')->findAll();

        return $this->render('qvuser/index.html.twig', array(
            'qvUsers' => $qvUsers,
        ));
    }

    /**
     * Creates a new qvUser entity.
     *
     * @Route("/new", name="user_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $qvUser = new qvUser();
        $form = $this->createForm('AppBundle\Form\qvUserType', $qvUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvUser);
            $em->flush();

            return $this->redirectToRoute('user_show', array('id' => $qvUser->getId()));
        }

        return $this->render('qvuser/new.html.twig', array(
            'qvUser' => $qvUser,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a qvUser entity.
     *
     * @Route("/{id}", name="user_show")
     * @Method("GET")
     */
    public function showAction(qvUser $qvUser)
    {
        $deleteForm = $this->createDeleteForm($qvUser);

        return $this->render('qvuser/show.html.twig', array(
            'qvUser' => $qvUser,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing qvUser entity.
     *
     * @Route("/{id}/edit", name="user_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, qvUser $qvUser)
    {
        $deleteForm = $this->createDeleteForm($qvUser);
        $editForm = $this->createForm('AppBundle\Form\qvUserType', $qvUser);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvUser);
            $em->flush();

            return $this->redirectToRoute('user_edit', array('id' => $qvUser->getId()));
        }

        return $this->render('qvuser/edit.html.twig', array(
            'qvUser' => $qvUser,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a qvUser entity.
     *
     * @Route("/{id}", name="user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, qvUser $qvUser)
    {
        $form = $this->createDeleteForm($qvUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($qvUser);
            $em->flush();
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * Creates a form to delete a qvUser entity.
     *
     * @param qvUser $qvUser The qvUser entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(qvUser $qvUser)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('id' => $qvUser->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

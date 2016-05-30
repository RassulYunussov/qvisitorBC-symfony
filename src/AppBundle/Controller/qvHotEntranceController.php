<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\qvHotEntrance;
use AppBundle\Form\qvHotEntranceType;

/**
 * qvHotEntrance controller.
 *
 * @Route("/hotentrance")
 */
class qvHotEntranceController extends Controller
{
    /**
     * Lists all qvHotEntrance entities.
     *
     * @Route("/", name="hotentrance_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $qvHotEntrances = $em->getRepository('AppBundle:qvHotEntrance')->findAll();

        return $this->render('qvhotentrance/index.html.twig', array(
            'qvHotEntrances' => $qvHotEntrances,
        ));
    }

    /**
     * Creates a new qvHotEntrance entity.
     *
     * @Route("/new", name="hotentrance_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $qvHotEntrance = new qvHotEntrance();
        $form = $this->createForm('AppBundle\Form\qvHotEntranceType', $qvHotEntrance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvHotEntrance);
            $em->flush();

            return $this->redirectToRoute('hotentrance_show', array('id' => $qvHotEntrance->getId()));
        }

        return $this->render('qvhotentrance/new.html.twig', array(
            'qvHotEntrance' => $qvHotEntrance,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a qvHotEntrance entity.
     *
     * @Route("/{id}", name="hotentrance_show")
     * @Method("GET")
     */
    public function showAction(qvHotEntrance $qvHotEntrance)
    {
        $deleteForm = $this->createDeleteForm($qvHotEntrance);

        return $this->render('qvhotentrance/show.html.twig', array(
            'qvHotEntrance' => $qvHotEntrance,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing qvHotEntrance entity.
     *
     * @Route("/{id}/edit", name="hotentrance_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, qvHotEntrance $qvHotEntrance)
    {
        $deleteForm = $this->createDeleteForm($qvHotEntrance);
        $editForm = $this->createForm('AppBundle\Form\qvHotEntranceType', $qvHotEntrance);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvHotEntrance);
            $em->flush();

            return $this->redirectToRoute('hotentrance_edit', array('id' => $qvHotEntrance->getId()));
        }

        return $this->render('qvhotentrance/edit.html.twig', array(
            'qvHotEntrance' => $qvHotEntrance,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a qvHotEntrance entity.
     *
     * @Route("/{id}", name="hotentrance_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, qvHotEntrance $qvHotEntrance)
    {
        $form = $this->createDeleteForm($qvHotEntrance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($qvHotEntrance);
            $em->flush();
        }

        return $this->redirectToRoute('hotentrance_index');
    }

    /**
     * Creates a form to delete a qvHotEntrance entity.
     *
     * @param qvHotEntrance $qvHotEntrance The qvHotEntrance entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(qvHotEntrance $qvHotEntrance)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('hotentrance_delete', array('id' => $qvHotEntrance->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

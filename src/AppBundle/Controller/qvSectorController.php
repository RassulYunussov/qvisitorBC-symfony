<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\qvSector;
use AppBundle\Form\qvSectorType;

/**
 * qvSector controller.
 *
 * @Route("/sector")
 */
class qvSectorController extends Controller
{
    /**
     * Lists all qvSector entities.
     *
     * @Route("/", name="sector_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $qvSectors = $em->getRepository('AppBundle:qvSector')->findAll();

        return $this->render('AppBundle:qvsector:index.html.twig', array(
            'qvSectors' => $qvSectors,
        ));
    }

    /**
     * Creates a new qvSector entity.
     *
     * @Route("/new", name="sector_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $qvSector = new qvSector();
        $form = $this->createForm('AppBundle\Form\qvSectorType', $qvSector);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvSector);
            $em->flush();

            return $this->redirectToRoute('sector_show', array('id' => $qvSector->getId()));
        }

        return $this->render('AppBundle:qvsector:new.html.twig', array(
            'qvSector' => $qvSector,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a qvSector entity.
     *
     * @Route("/{id}", name="sector_show")
     * @Method("GET")
     */
    public function showAction(qvSector $qvSector)
    {
        $deleteForm = $this->createDeleteForm($qvSector);

        return $this->render('AppBundle:qvsector:show.html.twig', array(
            'qvSector' => $qvSector,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing qvSector entity.
     *
     * @Route("/{id}/edit", name="sector_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, qvSector $qvSector)
    {
        $deleteForm = $this->createDeleteForm($qvSector);
        $editForm = $this->createForm('AppBundle\Form\qvSectorType', $qvSector);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvSector);
            $em->flush();

            return $this->redirectToRoute('sector_edit', array('id' => $qvSector->getId()));
        }

        return $this->render('AppBundle:qvsector:edit.html.twig', array(
            'qvSector' => $qvSector,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a qvSector entity.
     *
     * @Route("/{id}", name="sector_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, qvSector $qvSector)
    {
        $form = $this->createDeleteForm($qvSector);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($qvSector);
            $em->flush();
        }

        return $this->redirectToRoute('sector_index');
    }

    /**
     * Creates a form to delete a qvSector entity.
     *
     * @param qvSector $qvSector The qvSector entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(qvSector $qvSector)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sector_delete', array('id' => $qvSector->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

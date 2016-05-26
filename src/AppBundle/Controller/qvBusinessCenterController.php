<?php
//irismet
namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\qvBusinessCenter;
use AppBundle\Form\qvBusinessCenterType;

/**
 * qvBusinessCenter controller.
 *
 * @Route("/bc")
 */
class qvBusinessCenterController extends Controller
{
    /**
     * Lists all qvBusinessCenter entities.
     *
     * @Route("/", name="bc_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $qvBusinessCenters = $em->getRepository('AppBundle:qvBusinessCenter')->findAll();

        return $this->render('qvbusinesscenter/index.html.twig', array(
            'qvBusinessCenters' => $qvBusinessCenters,
        ));
    }

    /**
     * Creates a new qvBusinessCenter entity.
     *
     * @Route("/new", name="bc_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $qvBusinessCenter = new qvBusinessCenter();
        $form = $this->createForm('AppBundle\Form\qvBusinessCenterType', $qvBusinessCenter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvBusinessCenter);
            $em->flush();

            return $this->redirectToRoute('bc_show', array('id' => $qvBusinessCenter->getId()));
        }

        return $this->render('qvbusinesscenter/new.html.twig', array(
            'qvBusinessCenter' => $qvBusinessCenter,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a qvBusinessCenter entity.
     *
     * @Route("/{id}", name="bc_show")
     * @Method("GET")
     */
    public function showAction(qvBusinessCenter $qvBusinessCenter)
    {
        $deleteForm = $this->createDeleteForm($qvBusinessCenter);

        return $this->render('qvbusinesscenter/show.html.twig', array(
            'qvBusinessCenter' => $qvBusinessCenter,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing qvBusinessCenter entity.
     *
     * @Route("/{id}/edit", name="bc_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, qvBusinessCenter $qvBusinessCenter)
    {
        $deleteForm = $this->createDeleteForm($qvBusinessCenter);
        $editForm = $this->createForm('AppBundle\Form\qvBusinessCenterType', $qvBusinessCenter);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qvBusinessCenter);
            $em->flush();

            return $this->redirectToRoute('bc_edit', array('id' => $qvBusinessCenter->getId()));
        }

        return $this->render('qvbusinesscenter/edit.html.twig', array(
            'qvBusinessCenter' => $qvBusinessCenter,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a qvBusinessCenter entity.
     *
     * @Route("/{id}", name="bc_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, qvBusinessCenter $qvBusinessCenter)
    {
        $form = $this->createDeleteForm($qvBusinessCenter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($qvBusinessCenter);
            $em->flush();
        }

        return $this->redirectToRoute('bc_index');
    }

    /**
     * Creates a form to delete a qvBusinessCenter entity.
     *
     * @param qvBusinessCenter $qvBusinessCenter The qvBusinessCenter entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(qvBusinessCenter $qvBusinessCenter)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('bc_delete', array('id' => $qvBusinessCenter->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class AdminBCController extends Controller
{
 /**
     * @Route("/adminbc")
     * @Method("GET")
     */
    public function adminbcAction()
    {
    	
        return $this->render('AppBundle:Adminbc:adminbc.html.twig', array(
        		
        ));
    }	
	
	
		 /**
     * @Route("/leasers_control")
     * @Method("GET")
     */
    public function leasers_controlAction()
    {
    	
        return $this->render('AppBundle:Adminbc:leaserscontrol.html.twig', array(
        		
        ));
    }
	
		 /**
     * @Route("/show_leaser")
     * @Method("GET")
     */
    public function show_leaserAction()
    {
    	
        return $this->render('AppBundle:Adminbc:showleaser.html.twig', array(
        		
        ));
    }
	
		 /**
     * @Route("/edit_leaser")
     * @Method("GET")
     */
    public function edit_leaserAction()
    {
    	
        return $this->render('AppBundle:Adminbc:editleaser.html.twig', array(
        		
        ));
    }
	
		 /**
     * @Route("/create_leaser")
     * @Method("GET")
     */
    public function create_leaserAction()
    {
    	
        return $this->render('AppBundle:Adminbc:createleaser.html.twig', array(
        		
        ));
    }
	
	
	/**
     * @Route("/contracts_control")
     * @Method("GET")
     */
    public function contracts_controlAction()
    {
    	
        return $this->render('AppBundle:Adminbc:contractscontrol.html.twig', array(
        		
        ));
    }
	
	 /**
     * @Route("/show_contract")
     * @Method("GET")
     */
    public function show_contractAction()
    {
    	
        return $this->render('AppBundle:Adminbc:showcontract.html.twig', array(
        		
        ));
    }	
	
	 /**
     * @Route("/edit_contract")
     * @Method("GET")
     */
    public function edit_contractAction()
    {
    	
        return $this->render('AppBundle:Adminbc:editcontract.html.twig', array(
        		
        ));
    }
		 /**
     * @Route("/create_contract")
     * @Method("GET")
     */
    public function createcontractAction()
    {
    	
        return $this->render('AppBundle:Adminbc:createcontract.html.twig', array(
        		
        ));
    }
	
	/**
     * @Route("/checkpoints_control")
     * @Method("GET")
     */
    public function checkpoints_controlAction()
    {
    	
        return $this->render('AppBundle:Adminbc:checkpointscontrol.html.twig', array(
        		
        ));
    }
	
		 /**
     * @Route("/show_checkpoint")
     * @Method("GET")
     */
    public function show_checkpointAction()
    {
    	
        return $this->render('AppBundle:Adminbc:showcheckpoint.html.twig', array(
        		
        ));
    }
	
		 /**
     * @Route("/edit_checkpoint")
     * @Method("GET")
     */
    public function edit_checkpointAction()
    {
    	
        return $this->render('AppBundle:Adminbc:editcheckpoint.html.twig', array(
        		
        ));
    }
	
	
		 /**
     * @Route("/create_checkpoint")
     * @Method("GET")
     */
    public function create_checkpointAction()
    {
    	
        return $this->render('AppBundle:Adminbc:createcheckpoint.html.twig', array(
        		
        ));
    }
	
		
	/**
     * @Route("/sectors_control")
     * @Method("GET")
     */
    public function sectors_controlAction()
    {
    	
        return $this->render('AppBundle:Adminbc:sectorscontrol.html.twig', array(
        		
        ));
    }
	
		 /**
     * @Route("/show_sector")
     * @Method("GET")
     */
    public function show_sectorAction()
    {
    	
        return $this->render('AppBundle:Adminbc:showsector.html.twig', array(
        		
        ));
    }
	
		 /**
     * @Route("/edit_sector")
     * @Method("GET")
     */
    public function edit_sectorAction()
    {
    	
        return $this->render('AppBundle:Adminbc:editsector.html.twig', array(
        		
        ));
    }
	
		 /**
     * @Route("/create_sector")
     * @Method("GET")
     */
    public function create_sectorAction()
    {
    	
        return $this->render('AppBundle:Adminbc:Createsector.html.twig', array(
        		
        ));
    }
	
		 /**
     * @Route("/test")
     * @Method("GET")
     */
    public function testAction()
    {
    	
        return $this->render('AppBundle:Adminbc:test.html.twig', array(
        		
        ));
    }
}

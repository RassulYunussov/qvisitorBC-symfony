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
    	
        return $this->render('AppBundle:Adminbc:leasers_control/leaserscontrol.html.twig', array(
        		
        ));
    }
	
		 /**
     * @Route("/show_leaser")
     * @Method("GET")
     */
    public function show_leaserAction()
    {
    	
        return $this->render('AppBundle:Adminbc:leasers_control/leasers_control/showleaser.html.twig', array(
        		
        ));
    }
	
		 /**
     * @Route("/edit_leaser")
     * @Method("GET")
     */
    public function edit_leaserAction()
    {
    	
        return $this->render('AppBundle:Adminbc:leasers_control/leasers_control/editleaser.html.twig', array(
        		
        ));
    }
	
		 /**
     * @Route("/create_leaser")
     * @Method("GET")
     */
    public function create_leaserAction()
    {
    	
        return $this->render('AppBundle:Adminbc:leasers_control/leasers_control/createleaser.html.twig', array(
        		
        ));
    }
	
	
	/**
     * @Route("/contracts_control")
     * @Method("GET")
     */
    public function contracts_controlAction()
    {
    	
        return $this->render('AppBundle:Adminbc:leasers_control/contractscontrol.html.twig', array(
        		
        ));
    }
	
	 /**
     * @Route("/show_contract")
     * @Method("GET")
     */
    public function show_contractAction()
    {
    	
        return $this->render('AppBundle:Adminbc:leasers_control/showcontract.html.twig', array(
        		
        ));
    }	
	
	 /**
     * @Route("/edit_contract")
     * @Method("GET")
     */
    public function edit_contractAction()
    {
    	
        return $this->render('AppBundle:Adminbc:leasers_control/editcontract.html.twig', array(
        		
        ));
    }
		 /**
     * @Route("/create_contract")
     * @Method("GET")
     */
    public function createcontractAction()
    {
    	
        return $this->render('AppBundle:Adminbc:leasers_control/createcontract.html.twig', array(
        		
        ));
    }
	
	/**
     * @Route("/checkpoints_control")
     * @Method("GET")
     */
    public function checkpoints_controlAction()
    {
    	
        return $this->render('AppBundle:Adminbc:buildings_control/checkpoints_control/checkpointscontrol.html.twig', array(
        		
        ));
    }
	
		 /**
     * @Route("/show_checkpoint")
     * @Method("GET")
     */
    public function show_checkpointAction()
    {
    	
        return $this->render('AppBundle:Adminbc:buildings_control/checkpoints_control/showcheckpoint.html.twig', array(
        		
        ));
    }
	
		 /**
     * @Route("/edit_checkpoint")
     * @Method("GET")
     */
    public function edit_checkpointAction()
    {
    	
        return $this->render('AppBundle:Adminbc:buildings_control/checkpoints_control/editcheckpoint.html.twig', array(
        		
        ));
    }
	
	
		 /**
     * @Route("/create_checkpoint")
     * @Method("GET")
     */
    public function create_checkpointAction()
    {
    	
        return $this->render('AppBundle:Adminbc:buildings_control/checkpoints_control/createcheckpoint.html.twig', array(
        		
        ));
    }
	
		
	/**
     * @Route("/sectors_control")
     * @Method("GET")
     */
    public function sectors_controlAction()
    {
    	
        return $this->render('AppBundle:Adminbc:buildings_control/sectors_control/sectorscontrol.html.twig', array(
        		
        ));
    }
	
		 /**
     * @Route("/show_sector")
     * @Method("GET")
     */
    public function show_sectorAction()
    {
    	
        return $this->render('AppBundle:buildings_control/sectors_control/Adminbc:showsector.html.twig', array(
        		
        ));
    }
	
		 /**
     * @Route("/edit_sector")
     * @Method("GET")
     */
    public function edit_sectorAction()
    {
    	
        return $this->render('AppBundle:Adminbc:buildings_control/sectors_control/editsector.html.twig', array(
        		
        ));
    }
	
		 /**
     * @Route("/create_sector")
     * @Method("GET")
     */
    public function create_sectorAction()
    {
    	
        return $this->render('AppBundle:Adminbc:buildings_control/sectors_control/Createsector.html.twig', array(
        		
        ));
    }
	
		 /**
     * @Route("/buildings_control")
     * @Method("GET")
     */
    public function buildings_controlAction()
    {
    	
        return $this->render('AppBundle:Adminbc:buildings_control/buildingscontrol.html.twig', array(
        		
        ));
    }
	 /**
     * @Route("/floors_control")
     * @Method("GET")
     */
    public function floors_controlAction()
    {
    	
        return $this->render('AppBundle:Adminbc:buildings_control/floors_control/floorscontrol.html.twig', array(
        		
        ));
    }		
	
		 /**
     * @Route("/edit_floor")
     * @Method("GET")
     */
    public function edit_floorAction()
    {
    	
        return $this->render('AppBundle:Adminbc:buildings_control/floors_control/editfloor.html.twig', array(
        		
        ));
    }
	
		 /**
     * @Route("/show_floor")
     * @Method("GET")
     */
    public function show_floorAction()
    {
    	
        return $this->render('AppBundle:Adminbc:buildings_control/floors_control/showfloor.html.twig', array(
        		
        ));
    }
	
		/**
     * @Route("/create_floor")
     * @Method("GET")
     */
    public function create_floorAction()
    {
    	
        return $this->render('AppBundle:Adminbc:buildings_control/floors_control/createfloor.html.twig', array(
        		
        ));
    }
}

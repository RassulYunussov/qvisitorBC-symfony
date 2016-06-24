<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/adminbc")
 * @Method("GET")
 */

class AdminBCController extends Controller
{
 /**
     * @Route("/index")
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
     * @Route("/leasers_control/show_leaser")
     * @Method("GET")
     */
    public function show_leaserAction()
    {
    	
        return $this->render('AppBundle:Adminbc:leasers_control/showleaser.html.twig', array(
        		
        ));
    }
	
	 /**
     * @Route("/leasers_control/edit_leaser")
     * @Method("GET")
     */
    public function edit_leaserAction()
    {
    	
        return $this->render('AppBundle:Adminbc:leasers_control/editleaser.html.twig', array(
        		
        ));
    }
	
		 /**
     * @Route("/leasers_control/create_leaser")
     * @Method("GET")
     */
    public function create_leaserAction()
    {
    	
        return $this->render('AppBundle:Adminbc:leasers_control/createleaser.html.twig', array(
        		
        ));
    }
	
	
	/**
     * @Route("/leasers_control/contracts_control")
     * @Method("GET")
     */
    public function contracts_controlAction()
    {
    	
        return $this->render('AppBundle:Adminbc:leasers_control/contractscontrol.html.twig', array(
        		
        ));
    }
	
	 /**
     * @Route("/leasers_control/show_contract")
     * @Method("GET")
     */
    public function show_contractAction()
    {
    	
        return $this->render('AppBundle:Adminbc:leasers_control/showcontract.html.twig', array(
        		
        ));
    }	
	
	 /**
     * @Route("/leasers_control/edit_contract")
     * @Method("GET")
     */
    public function edit_contractAction()
    {
    	
        return $this->render('AppBundle:Adminbc:leasers_control/editcontract.html.twig', array(
        		
        ));
    }
		 /**
     * @Route("/leasers_control/create_contract")
     * @Method("GET")
     */
    public function createcontractAction()
    {
    	
        return $this->render('AppBundle:Adminbc:leasers_control/createcontract.html.twig', array(
        		
        ));
    }
	
	
	
		 /**
     * @Route("/buildings_control/show_checkpoint")
     * @Method("GET")
     */
    public function show_checkpointAction()
    {
    	
        return $this->render('AppBundle:Adminbc:buildings_control/checkpoints_control/showcheckpoint.html.twig', array(
        		
        ));
    }
	
		 /**
     * @Route("/buildings_control/edit_checkpoint")
     * @Method("GET")
     */
    public function edit_checkpointAction()
    {
    	
        return $this->render('AppBundle:Adminbc:buildings_control/checkpoints_control/editcheckpoint.html.twig', array(
        		
        ));
    }
	
	
		 /**
     * @Route("/buildings_control/create_checkpoint")
     * @Method("GET")
     */
    public function create_checkpointAction()
    {
    	
        return $this->render('AppBundle:Adminbc:buildings_control/checkpoints_control/createcheckpoint.html.twig', array(
        		
        ));
    }
	
	
		 /**
     * @Route("/buildings_control/show_sector")
     * @Method("GET")
     */
    public function show_sectorAction()
    {
    	
        return $this->render('AppBundle:Adminbc:buildings_control/sectors_control/showsector.html.twig', array(
        		
        ));
    }
	
		 /**
     * @Route("/buildings_control/edit_sector")
     * @Method("GET")
     */
    public function edit_sectorAction()
    {
    	
        return $this->render('AppBundle:Adminbc:buildings_control/sectors_control/editsector.html.twig', array(
        		
        ));
    }
	
		 /**
     * @Route("/buildings_control/create_sector")
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
     * @Route("/buildings_control/show_building")
     * @Method("GET")
     */
    public function show_buildingAction()
    {

        return $this->render('AppBundle:Adminbc:buildings_control/show_building.html.twig', array(

        ));
    }

    /**
     * @Route("/buildings_control/edit_building")
     * @Method("GET")
     */
    public function edit_buildingAction()
    {

        return $this->render('AppBundle:Adminbc:buildings_control/edit_building.html.twig', array(

        ));
    }

    /**
     * @Route("/buildings_control/create_building")
     * @Method("GET")
     */
    public function create_buildingsAction()
    {

        return $this->render('AppBundle:Adminbc:buildings_control/create_building.html.twig', array(

        ));
    }

	
		 /**
     * @Route("/buildings_control/edit_floor")
     * @Method("GET")
     */
    public function edit_floorAction()
    {
    	
        return $this->render('AppBundle:Adminbc:buildings_control/floors_control/editfloor.html.twig', array(
        		
        ));
    }
	
		 /**
     * @Route("/buildings_control/show_floor")
     * @Method("GET")
     */
    public function show_floorAction()
    {
    	
        return $this->render('AppBundle:Adminbc:buildings_control/floors_control/showfloor.html.twig', array(
        		
        ));
    }
	
		/**
     * @Route("/buildings_control/create_floor")
     * @Method("GET")
     */
    public function create_floorAction()
    {
    	
        return $this->render('AppBundle:Adminbc:buildings_control/floors_control/createfloor.html.twig', array(
        		
        ));
    }
}

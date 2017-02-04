<?php
// src/AppBundle/EventListener/ExceptionListener.php
namespace AppBundle\EventListener;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\qvUserPassport;

class LoginListener
{
	protected $em;
	function __construct(EntityManager $em)
	{
		$this->em = $em;
	}
	
	public function onLogin(InteractiveLoginEvent $event)
	{
		/*
		$user = $event->getAuthenticationToken()->getUser();
		$userPassport=$this->em->getRepository('AppBundle:qvUserPassport')->findOneBy(array('user'=>$user->getId()));
		$request = $event->getRequest();
		$session = $request->getSession();
		$session->set('_username', $userPassport->getFirstName().' '.$userPassport->getLastName());*/
	}
}
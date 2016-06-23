<?php
use AppBundle\Entity\qvUser;
// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\qvUser;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
	/**
	 * @var ContainerInterface
	 */
	private $container;
	public function setContainer(ContainerInterface $container = null)
	{
		$this->container = $container;
	}
	public function load(ObjectManager $manager)
	{
		// the 'security.password_encoder' service requires Symfony 2.6 or higher
		$encoder = $this->container->get('security.password_encoder');
		
		$adminUser = new qvUser();
		$saUser = new qvUser();
		$leaserUser = new qvUser();
		$checkpointUser = new qvUser();
		
		$adminPassword = $encoder->encodePassword($adminUser, 'admin');
		$saPassword = $encoder->encodePassword($saUser, 'sa');
		$leaserPassword = $encoder->encodePassword($leaserUser, 'leaser');
		$checkpointPassword = $encoder->encodePassword($checkpointUser, 'checkpoint');
	
		
		$adminUser->setLogin('admin@qvisitor.com');
		$adminUser->setPassword($adminPassword);
		$adminUser->setRole($this->getReference('adminRole'));
		$adminUser->setDisabled(false);
		
		$saUser->setLogin('sa@qvisitor.com');
		$saUser->setPassword($saPassword);
		$saUser->setRole($this->getReference('saRole'));
		$saUser->setDisabled(false);
		
		$leaserUser->setLogin('leaser@qvisitor.com');
		$leaserUser->setPassword($leaserPassword);
		$leaserUser->setRole($this->getReference('leaserRole'));
		$leaserUser->setDisabled(false);
		
		$checkpointUser->setLogin('checkpoint@qvisitor.com');
		$checkpointUser->setPassword($checkpointPassword);
		$checkpointUser->setRole($this->getReference('checkpointRole'));
		$checkpointUser->setDisabled(false);
		
		$manager->persist($adminUser);
		$manager->persist($saUser);
		$manager->persist($leaserUser);
		$manager->persist($checkpointUser);

		$manager->flush();
	}
	public function getOrder()
	{
		// the order in which fixtures will be loaded
		// the lower the number, the sooner that this fixture is loaded
		return 2;
	}
}
<?php
// src/AppBundle/DataFixtures/ORM/LoadUserData.php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\qvRole;

class LoadRoleData extends AbstractFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$adminRole = new qvRole();
		$adminRole->setCode('ROLE_ADMIN');
		$adminRole->setName('Администратор БЦ');
		
		$saRole = new qvRole();
		$saRole->setCode('ROLE_SA');
		$saRole->setName('Администратор');
		
		$leaserRole = new qvRole();
		$leaserRole->setCode('ROLE_LEASER');
		$leaserRole->setName('Арендатор');
		
		$checkpointRole = new qvRole();
		$checkpointRole->setCode('ROLE_CHECKPOINT');
		$checkpointRole->setName('Охрана');
		

		$manager->persist($adminRole);
		$manager->persist($saRole);
		$manager->persist($leaserRole);
		$manager->persist($checkpointRole);
		
		$manager->flush();
		
		$this->addReference('adminRole',$adminRole);
		$this->addReference('saRole',$saRole);
		$this->addReference('leaserRole',$leaserRole);
		$this->addReference('checkpointRole',$checkpointRole);
	}
	public function getOrder()
	{
		// the order in which fixtures will be loaded
		// the lower the number, the sooner that this fixture is loaded
		return 1;
	}
}
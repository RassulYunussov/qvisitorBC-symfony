<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\qvBuilding;

class LoadBuildingData extends AbstractFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$buildingOne = new qvBuilding();
		$buildingTwo = new qvBuilding();
		
		$buildingOne->setBusinesscenter($this->getReference('bc'));
		$buildingOne->setName('Блок 1');
		
		$buildingTwo->setBusinesscenter($this->getReference('bc'));
		$buildingTwo->setName('Блок 2');
		

		$manager->persist($buildingOne);
		$manager->persist($buildingTwo);
		$manager->flush();
		$this->addReference('buildingOne',$buildingOne);
		$this->addReference('buildingTwo',$buildingTwo);

	}
	public function getOrder()
	{
		// the order in which fixtures will be loaded
		// the lower the number, the sooner that this fixture is loaded
		return 2;
	}
}
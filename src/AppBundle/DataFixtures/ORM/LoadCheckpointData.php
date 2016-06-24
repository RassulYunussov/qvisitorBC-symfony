<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\qvCheckpoint;

class LoadCheckpointData extends AbstractFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$checkpointOne = new qvCheckpoint();
		$checkpointTwo = new qvCheckpoint();
		$checkpointThree = new qvCheckpoint();
		$checkpointFour = new qvCheckpoint();
		
		$checkpointOne->setBuilding($this->getReference('buildingOne'));
		$checkpointTwo->setBuilding($this->getReference('buildingOne'));
		$checkpointThree->setBuilding($this->getReference('buildingTwo'));
		$checkpointFour->setBuilding($this->getReference('buildingTwo'));
		
		$checkpointOne->setName('КПП 1');
		$checkpointTwo->setName('КПП 2');
		$checkpointThree->setName('КПП 1');
		$checkpointFour->setName('КПП 2');
		

		$manager->persist($checkpointOne);
		$manager->persist($checkpointTwo);
		$manager->persist($checkpointThree);
		$manager->persist($checkpointFour);
		$manager->flush();
	}
	public function getOrder()
	{
		// the order in which fixtures will be loaded
		// the lower the number, the sooner that this fixture is loaded
		return 2;
	}
}
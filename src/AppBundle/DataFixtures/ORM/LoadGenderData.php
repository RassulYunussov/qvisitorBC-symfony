<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\qvGender;

class LoadGenderData extends AbstractFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$maleGender = new qvGender();
		$femaleGender = new qvGender();
		$maleGender->setName("М");
		$femaleGender->setName("Ж");
		
		$manager->persist($maleGender);
		$manager->persist($femaleGender);
	
		$manager->flush();
	}
	public function getOrder()
	{
		// the order in which fixtures will be loaded
		// the lower the number, the sooner that this fixture is loaded
		return 1;
	}
}
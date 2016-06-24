<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\qvOrderType;

class LoadOrderTypeData extends AbstractFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$singleOrderType = new qvOrderType();
		$multipleOrderType = new qvOrderType();
		$singleOrderType->setName('Одноразовый');
		$multipleOrderType->setName('Многоразовый');
		
		$manager->persist($singleOrderType);
		$manager->persist($multipleOrderType);

		$manager->flush();
	}
	public function getOrder()
	{
		// the order in which fixtures will be loaded
		// the lower the number, the sooner that this fixture is loaded
		return 1;
	}
}
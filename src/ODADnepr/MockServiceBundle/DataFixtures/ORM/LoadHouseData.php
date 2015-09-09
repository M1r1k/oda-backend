<?php

namespace ODADnepr\MockServiceBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use ODADnepr\MockServiceBundle\Entity\House;

Class LoadHouseData extends AbstractFixture implements OrderedFixtureInterface {
	public function load(ObjectManager $manager)
	{
		$house = new House();
		$house->setName(7);
		$house->setStreet($this->getReference('street'));

		$manager->persist($house);
		$manager->flush();

		$this->addReference('house', $house);
	}

	public function getOrder(){
		return 5;
	}
}
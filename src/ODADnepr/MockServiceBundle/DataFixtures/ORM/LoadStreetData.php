<?php

namespace ODADnepr\MockServiceBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use ODADnepr\MockServiceBundle\Entity\Street;

Class LoadStreetData extends AbstractFixture implements OrderedFixtureInterface {
	public function load(ObjectManager $manager)
	{
		$street = new Street();
		$street->setName('Карла Маркса');
		$street->setCity($this->getReference('city'));
		$street->setCityDistrict($this->getReference('cityDistrict'));

		$manager->persist($street);
		$manager->flush();

		$this->addReference('street', $street);
	}

	public function getOrder(){
		return 4;
	}
}
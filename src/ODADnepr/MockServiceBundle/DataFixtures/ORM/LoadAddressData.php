<?php

namespace ODADnepr\MockServiceBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use ODADnepr\MockServiceBundle\Entity\Address;

Class LoadAddressData extends AbstractFixture implements OrderedFixtureInterface {
	public function load(ObjectManager $manager)
	{
		$address = new Address();
		$address->setFlat('123B');
		$address->setDistrict($this->getReference('district'));
		$address->setCity($this->getReference('city'));
		$address->setStreet($this->getReference('street'));
		$address->setHouse($this->getReference('house'));

		$manager->persist($address);
		$manager->flush();

		$this->addReference('address', $address);
	}

	public function getOrder(){
		return 6;
	}
}
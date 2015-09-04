<?php

namespace ODADnepr\MockServiceBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use ODADnepr\MockServiceBundle\Entity\City;

Class LoadCityData extends AbstractFixture implements OrderedFixtureInterface {
	public function load(ObjectManager $manager)
	{
		$city = new City();
		$city->setName('Dnepr');
		$city->setDistrict($this->getReference('district'));

		$manager->persist($city);
		$manager->flush();

		$this->addReference('city', $city);
	}

	public function getOrder(){
		return 2;
	}
}
<?php

namespace ODADnepr\MockServiceBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use ODADnepr\MockServiceBundle\Entity\CityDistrict;

Class LoadCityDistrictData extends AbstractFixture implements OrderedFixtureInterface {
	public function load(ObjectManager $manager)
	{
		$cityDistrict = new CityDistrict();
		$cityDistrict->setName('Red Stone');
		$cityDistrict->setCityId($this->getReference('city')->getId());

		$manager->persist($cityDistrict);
		$manager->flush();

		$this->addReference('cityDistrict', $cityDistrict);
	}

	public function getOrder(){
		return 3;
	}
}
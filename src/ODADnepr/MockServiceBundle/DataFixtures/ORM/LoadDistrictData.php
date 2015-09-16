<?php

namespace ODADnepr\MockServiceBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ODADnepr\MockServiceBundle\Entity\District;

Class LoadDistrictData extends AbstractFixture implements OrderedFixtureInterface {

	public function load(ObjectManager $manager)
	{
		$district = new District();
		$district->setName('Дніпропетровський');

		$manager->persist($district);
		$manager->flush();

		$this->addReference('district', $district);
	}

	public function getOrder(){
		return 1;
	}
}
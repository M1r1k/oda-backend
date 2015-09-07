<?php

namespace ODADnepr\MockServiceBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use ODADnepr\MockServiceBundle\Entity\TicketType;

Class LoadTicketTypeData extends AbstractFixture implements OrderedFixtureInterface {
	public function load(ObjectManager $manager)
	{
		$ticketType = new TicketType();
		$ticketType->setName('some type');

		$ticketType2 = new TicketType();
		$ticketType2->setName('another type');

		$manager->persist($ticketType);
		$manager->persist($ticketType2);

		$manager->flush();

		$this->addReference('ticketType', $ticketType);
		$this->addReference('ticketType-2', $ticketType2);
	}

	public function getOrder(){
		return 10;
	}
}
<?php

namespace ODADnepr\MockServiceBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use ODADnepr\MockServiceBundle\Entity\TicketState;

Class LoadTicketStateData extends AbstractFixture implements OrderedFixtureInterface {
	public function load(ObjectManager $manager)
	{
		$ticketState = new TicketState();
		$ticketState->setName('some state');

		$ticketState2 = new TicketState();
		$ticketState2->setName('another state');

		$manager->persist($ticketState);
		$manager->persist($ticketState2);

		$manager->flush();

		$this->addReference('ticketState', $ticketState);
		$this->addReference('ticketState-2', $ticketState2);
	}

	public function getOrder(){
		return 9;
	}
}
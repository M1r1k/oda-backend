<?php

namespace ODADnepr\MockServiceBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use ODADnepr\MockServiceBundle\Entity\TicketCategory;

Class LoadTicketCategoryData extends AbstractFixture implements OrderedFixtureInterface {
	public function load(ObjectManager $manager)
	{
		$ticketCategory = new TicketCategory();
		$ticketCategory->setName('General');

		$ticketCategory2 = new TicketCategory();
		$ticketCategory2->setName('Specific');

		$manager->persist($ticketCategory);
		$manager->persist($ticketCategory2);

		$manager->flush();

		$this->addReference('ticketCategory', $ticketCategory);
		$this->addReference('ticketCategory-2', $ticketCategory2);
	}

	public function getOrder(){
		return 8;
	}
}
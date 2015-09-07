<?php

namespace ODADnepr\MockServiceBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use ODADnepr\MockServiceBundle\Entity\Ticket;

Class LoadManyTicketsData extends AbstractFixture implements OrderedFixtureInterface {

	public function load(ObjectManager $manager){
		$tickets = array();

		$tickets[] = $this->createTicket(
			array(
				'title' => 'fst ticket',
				'body' => 'first'
			),
			$this->getReference('ticketCategory'),
			$this->getReference('ticketType'),
			$this->getReference('ticketState')
		);

		$tickets[] = $this->createTicket(array(
				'title' => 'scn ticket',
				'body' => 'second'
			),
			$this->getReference('ticketCategory-2'),
			$this->getReference('ticketType'),
			$this->getReference('ticketState')
		);

		$tickets[] = $this->createTicket(array(
				'title' => 'third ticket',
				'body' => 'third'
			),
			$this->getReference('ticketCategory-2'),
			$this->getReference('ticketType'),
			$this->getReference('ticketState')
		);

		$tickets[] = $this->createTicket(array(
				'title' => 'fourth ticket',
				'body' => 'fourth'
			),
			$this->getReference('ticketCategory-2'),
			$this->getReference('ticketType'),
			$this->getReference('ticketState')
		);

		$tickets[] = $this->createTicket(array(
				'title' => 'fifth ticket',
				'body' => 'fifth'
			),
			$this->getReference('ticketCategory-2'),
			$this->getReference('ticketType'),
			$this->getReference('ticketState-2')
		);

		$tickets[] = $this->createTicket(array(
				'title' => 'sixth ticket',
				'body' => 'sixth'
			),
			$this->getReference('ticketCategory'),
			$this->getReference('ticketType-2'),
			$this->getReference('ticketState-2')
		);

		foreach ($tickets as $t) {
			$manager->persist($t);
		}

		$manager->flush();
	}

	private function createTicket($data, $category, $type, $state) {
		$ticket = new Ticket();

		$ticket->setTitle($data['title']);
		$ticket->setBody($data['body']);
		$ticket->setCreatedDate(time());
		$ticket->setTicketId("ticketID");
		$ticket->setAddress($this->getReference('address'));
		$ticket->setUser($this->getReference('user'));
		$ticket->setCategory($category);
		$ticket->setType($type);
		$ticket->setState($state);

		return $ticket;
	}

	public function getOrder(){
		return 11;
	}
}
<?php

namespace ODADnepr\MockServiceBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use ODADnepr\MockServiceBundle\Entity\User;

Class LoadUserData extends AbstractFixture implements OrderedFixtureInterface {
	public function load(ObjectManager $manager)
	{
		$user = new User();
		$user->setFirstName('Name');
		$user->setLastName('Lastname');
		$user->setMiddleName('Middlename');
		$user->setEmail('email@email.com');
		$user->setBirthday(time());
		$user->setPassword('password');
		$user->setAddress($this->getReference('address'));

		$manager->persist($user);
		$manager->flush();

		$this->addReference('user', $user);
	}

	public function getOrder(){
		return 7;
	}
}
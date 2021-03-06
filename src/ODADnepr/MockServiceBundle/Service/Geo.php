<?php

namespace ODADnepr\MockServiceBundle\Service;

use ODADnepr\MockServiceBundle\Service\GeoInterface;
use Doctrine\ORM\EntityManager;
use ODADnepr\MockServiceBundle\Entity\Address;
use ODADnepr\MockServiceBundle\Exception\GeoException;

Class Geo {
	private $geocoder;
	private $locale = 'ru';
	private $em;

	const UndefinedFlat = 'Не вказано';

	private static $replacements = array('вулиця', 'проспект');

	function __construct(EntityManager $em){
		$this->em = $em;

		$curl = new \Ivory\HttpAdapter\CurlHttpAdapter();
		$this->geocoder = new \Geocoder\Provider\GoogleMaps($curl, $this->locale);
	}

	public function extendGeo(GeoInterface &$entity) {
		if (!$entity->getAddress()) {
			if ($entity->getLatitude() && $entity->getLongitude()){
				$this->getAddress($entity);
			}
		} elseif (!$entity->getLatitude() || !$entity->getLongitude()) {
			$this->getCoordinates($entity);
		}
	}

	private function getAddress(GeoInterface &$entity) {
		$geodata = $this->geocoder->reverse($entity->getLatitude(), $entity->getLongitude())->first();

		$street = $this->prepareStreet($geodata->getStreetName());
		$params = array(
			'city' => $geodata->getLocality(),
			'strt' => '%'.$street.'%'
		);

		$house = $this->getHause(array_merge($params, array(
			'house' => $geodata->getStreetNumber()
		)));

		if ($house) {
			$address = $this->processAddress($house);
			$entity->setAddress($address);
		} else {
			throw new GeoException("Cannot detect address with given coordinates");
		}
	}

	private function getCoordinates(GeoInterface &$entity) {
		$addressString = $entity->getAddress()->getCity()->getName();
		$addressString .= ', ' . $entity->getAddress()->getStreet()->getName();
		$addressString .= ' ' . $entity->getAddress()->getHouse()->getName();

		$geo = $this->geocoder->geocode($addressString);

		$entity->setLatitude($geo->first()->getLatitude());
		$entity->setLongitude($geo->first()->getLongitude());
	}

	private function prepareStreet($street) {
		foreach (self::$replacements as $r) {
			$street = preg_replace('/^'.$r.'/', '', $street);
		}

		return trim($street);
	}

	private function getHause($params) {
		$qb = $this->em->createQueryBuilder();

		$qb->select('h')
				->from('ODADneprMockServiceBundle:house', 'h')
				->innerJoin('h.street', 's', 'WITH', 'h.street = s.id')
				->innerJoin('s.city', 'c', 'WITH', 's.city = c.id')
				->where('h.name LIKE :house AND s.name LIKE :strt AND c.name LIKE :city');

		$query = $qb->getQuery();
		$query->setParameters($params);

		$result = $query->getResult();

		return isset($result[0]) ? $result[0] : null;
	}

	private function processAddress($house) {
		$addresses = $this->em->getRepository('ODADneprMockServiceBundle:address')->findBy(array(
			'house' => $house,
			'street' => $house->getStreet(),
			'flat' => self::UndefinedFlat
		));

		if (!empty($addresses)){
			return $addresses[0];
		}

		$address = new Address();
		$address->setDistrict($house->getStreet()->getCity()->getDistrict());
		$address->setCity($house->getStreet()->getCity());
		$address->setStreet($house->getStreet());
		$address->setHouse($house);
		$address->setFlat(self::UndefinedFlat);

		$this->em->persist($address);
		$this->em->flush();

		return $address;
	}
}
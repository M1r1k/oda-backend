<?php

namespace ODADnepr\MockServiceBundle\Service;

use ODADnepr\MockServiceBundle\Entity\Address;
use ODADnepr\MockServiceBundle\Entity\GeoAddress;

interface GeoInterface {
	public function getGeoAddress();

		public function setGeoAddress(GeoAddress $geo_address);

	public function getAddress();

	public function setAddress(Address $address);
}

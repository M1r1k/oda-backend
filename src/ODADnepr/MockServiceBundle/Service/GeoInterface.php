<?php

namespace ODADnepr\MockServiceBundle\Service;

interface GeoInterface {
	public function getLatitude();

	public function setLatitude($latitude);

	public function getLongitude();

	public function setLongitude($longitude);

	public function getAddress();

	public function setAddress(\Address $address);
}
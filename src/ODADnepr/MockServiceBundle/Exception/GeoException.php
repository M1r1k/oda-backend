<?php

namespace ODADnepr\MockServiceBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

Class GeoException extends HttpException {
	function __construct($message = null, \Exception $previous = null, $code = 0) {
		parent::__construct(400, $message, $previous, array(), $code);
	}
}
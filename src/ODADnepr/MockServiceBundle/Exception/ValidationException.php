<?php

namespace ODADnepr\MockServiceBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

Class ValidationException extends HttpException {
	function __construct($message = null, \Exception $previous = null, $code = 0) {
		parent::__construct(422, $message, $previous, array(), $code);
	}
}
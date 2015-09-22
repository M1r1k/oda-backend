<?php

namespace ODADnepr\MockServiceBundle\Controller;

use Symfony\Component\HttpKernel\Exception\FlattenException;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
use Symfony\Bundle\TwigBundle\Controller\ExceptionController as BaseExceptionController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Util\Codes;
use ODADnepr\MockServiceBundle\Exception\ResponseException;

use FOS\RestBundle\Controller\FOSRestController;

class ExceptionController extends FOSRestController {

	public function showAction(Request $request, FlattenException $exception, DebugLoggerInterface $logger = null, $format = 'html') {

		return new Response($this->createResponse($exception), $exception->getStatusCode(), $exception->getHeaders());
	}

	private function createResponse($exception) {
		$message = json_decode($exception->getMessage(), true);

		$res = array(
			'code' => $exception->getStatusCode(),
			'message' => $message ? $message : $exception->getMessage()
		);

		switch ($exception->getStatusCode()) {
			case 422:
				$res['message'] = 'Validation error';
				$res['attributes'] = array();

				foreach ($message as $k => $v) {
					$res['attributes'][$v['property_path']] = array(
						array(
							'message' => $v['message']
						)
					);
				}

				break;
		}

		if ($this->get('kernel')->getEnvironment() == 'dev' && $exception->getStatusCode() >= 500) {
			$res['trace'] = $exception->getTrace();
		}

		return json_encode($res);
	}
}
<?php
namespace ODADnepr\MockServiceBundle\EventListener;

use ODADnepr\MockServiceBundle\Controller\AdminAPIController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

use ODADnepr\MockServiceBundle\Entity\Admin;

class TokenListener
{
	public function __construct($em)
	{
		$this->adminRepository = $em->getRepository('ODADneprMockServiceBundle:Admin');
	}

	public function onKernelController(FilterControllerEvent $event)
	{
		$controller = $event->getController();

		/*
		 * $controller passed can be either a class or a Closure.
		 * This is not usual in Symfony but it may happen.
		 * If it is a class, it comes in array format
		 */
		if (!is_array($controller)) {
			return;
		}

		if ($controller[0] instanceof AdminAPIController) {
			$admin = $this->adminRepository->findOneBy(array('token' => $event->getRequest()->query->get('token')));

			if (!$admin) {
				throw new AccessDeniedHttpException('This action needs a valid token!');
			}
		}
	}
}
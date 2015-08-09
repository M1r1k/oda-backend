<?php

/**
 * @file
 * Contains BaseController.php
 */

namespace ODADnepr\MockServiceBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

class BaseController extends FOSRestController {

  /**
   * @var \Doctrine\Common\Persistence\ObjectManager
   */
  protected $entityManager;

  /**
   * @var \JMS\Serializer\SerializerInterface
   */
  protected $serializer;


  public function manualConstruct()
  {
    $this->entityManager = $this->getDoctrine()->getManager();
    $this->serializer = $this->get('serializer');
  }

  public function manualResponseHandler($data) {
    $view = $this->view($data);
    return $this->handleView($view);
  }
}

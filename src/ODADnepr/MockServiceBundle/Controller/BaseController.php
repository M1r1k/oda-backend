<?php

/**
 * @file
 * Contains BaseController.php
 */

namespace ODADnepr\MockServiceBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use ODADnepr\MockServiceBundle\Entity\OdaEntityManager;
use ODADnepr\MockServiceBundle\Entity\Ticket;

class BaseController extends FOSRestController {

  /**
   * @var \Doctrine\Common\Persistence\ObjectManager
   */
  protected $entityManager;

  /**
   * @var \JMS\Serializer\SerializerInterface
   */
  protected $serializer;

  /**
   * @var OdaEntityManager
   */
  protected $odaManager;

  public function manualConstruct()
  {
    $this->entityManager = $this->getDoctrine()->getManager();
    $this->serializer = $this->get('serializer');
    $this->odaManager = $this->get('oda.oda_entity_manager');
  }

  public function manualResponseHandler($data) {
    $view = $this->view($data);
    return $this->handleView($view);
  }
}

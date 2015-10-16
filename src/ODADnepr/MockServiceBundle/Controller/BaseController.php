<?php

/**
 * @file
 * Contains BaseController.php
 */

namespace ODADnepr\MockServiceBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use ODADnepr\MockServiceBundle\Entity\OdaEntityManager;
use ODADnepr\MockServiceBundle\Entity\Ticket;
use JMS\Serializer\SerializationContext;
use ODADnepr\MockServiceBundle\Entity\User;

use ODADnepr\MockServiceBundle\Serializer\Exclusion\AnswersExclusionStrategy;


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

  protected $user;

  public function manualConstruct()
  {
    $this->entityManager = $this->getDoctrine()->getManager();
    $this->serializer = $this->get('serializer');
    $this->odaManager = $this->get('oda.oda_entity_manager');

    $user = $this->get('security.token_storage')->getToken()->getUser();
    if ($user instanceof User) {
      $this->user = $user;
    }
  }

  public function manualResponseHandler($data, $viewGroups = array()) {
    $context = SerializationContext::create()->setGroups(array_merge(array('Default'), $viewGroups));
    $context->addExclusionStrategy(new AnswersExclusionStrategy($this->user, $data));

    $view = $this->view($data);
    $view->setSerializationContext($context);

    return $this->handleView($view);
  }
}

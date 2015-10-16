<?php

namespace ODADnepr\MockServiceBundle\Serializer\Exclusion;

use JMS\Serializer\Exclusion\ExclusionStrategyInterface;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\Metadata\PropertyMetadata;
use JMS\Serializer\Context;
use ODADnepr\MockServiceBundle\Entity\Ticket;

class AnswersExclusionStrategy implements ExclusionStrategyInterface
{
  private $user;
  private $data;

  public function __construct($user, $data){
    $this->user = $user;
    $this->data = $data;
  }

  /**
   * {@inheritDoc}
   */
  public function shouldSkipClass(ClassMetadata $metadata, Context $navigatorContext)
  {
    return false;
  }

  /**
   * {@inheritDoc}
   */
  public function shouldSkipProperty(PropertyMetadata $property, Context $navigatorContext)
  {
    if (!($navigatorContext->getObject() instanceof Ticket)) {
      return false;
    }

    $name = $property->serializedName ?: $property->name;

    if ($name == 'answers') {
      if (!$this->user) {
        return true;
      }

      return $this->user != $navigatorContext->getObject()->getUser();
    } else {
      return false;
    }
  }
}
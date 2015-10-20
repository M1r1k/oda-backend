<?php

namespace ODADnepr\MockServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Admin
 *
 * @ORM\Table()
 * @ORM\Entity
 */
Class Admin {

  /**
   * @var integer
   *
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @var string
   *
   * @ORM\Column(name="token", type="string", length=255)
   */
  private $token;

  /**
   * Get id
   *
   * @return integer
   */
  public function getId()
  {
    return $this->id;
  }

  /**
  * Set token
  *
  * @param string $token
  * @return Address
  */
  public function setToken($token)
  {
    $this->token = $token;

    return $this;
  }

  /**
   * Get token
   *
   * @return string
   */
  public function getToken()
  {
    return $this->token;
  }
}
<?php

namespace ODADnepr\MockServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * TicketLike
 *
 * @ORM\Table()
 * @ORM\Entity
 *
 * @UniqueEntity(
 *  	fields={"fb_token", "ticket"}
 * )
 */
Class TicketLike {
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
	 * @Assert\NotBlank()
	 * @ORM\Column(name="fb_token", type="string", nullable=false)
	 */
	private $fb_token;

	/**
	 * @var Ticket
	 *
	 * @ORM\ManyToOne(targetEntity="Ticket", inversedBy="images")
	 * @ORM\JoinColumn(name="ticket_id", referencedColumnName="id")
	 */
	private $ticket;

	/**
	 * Set fb_token
	 *
	 * @param string $fbToken
	 * @return TicketLike
	 */
	public function setFbToken($fbToken)
	{
		$this->fb_token = $fbToken;

		return $this;
	}

	/**
	 * Get fb_token
	 *
	 * @return string
	 */
	public function getFbToken()
	{
		return $this->fb_token;
	}

	/**
	 * Set ticket
	 *
	 * @param \ODADnepr\MockServiceBundle\Entity\Ticket $ticket
	 * @return TicketLike
	 */
	public function setTicket(\ODADnepr\MockServiceBundle\Entity\Ticket $ticket = null)
	{
		$this->ticket = $ticket;

		return $this;
	}

	/**
	 * Get ticket
	 *
	 * @return \ODADnepr\MockServiceBundle\Entity\Ticket
	 */
	public function getTicket()
	{
		return $this->ticket;
	}
}

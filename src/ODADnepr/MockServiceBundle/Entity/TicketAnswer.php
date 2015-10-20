<?php

namespace ODADnepr\MockServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * TicketAnswer
 *
 * @ORM\Table()
 * @ORM\Entity
 * @Vich\Uploadable
 */
class TicketAnswer {
  /**
   * @var integer
   *
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @var File
   *
   * @Vich\UploadableField(mapping="ticket_answers", fileNameProperty="filename")
   */
  private $file;

  /**
   * @var Ticket
   *
   * @ORM\ManyToOne(targetEntity="Ticket", inversedBy="answers")
   * @ORM\JoinColumn(name="ticket_id", referencedColumnName="id")
   */
  private $ticket;

  /**
   * @var string
   *
   * @ORM\Column(name="filename", type="text")
   */
  private $filename;

  /**
   * Set file
   *
   * @param File $file
   * @return TicketFile
   */
  public function setFile(File $file)
  {
    $this->file = $file;

    return $this;
  }

  /**
   * Get file
   *
   * @return File
   */
  public function getFile()
  {
    return $this->file;
  }

  /**
   * Set ticket
   *
   * @param Ticket $ticket
   * @return TicketFile
   */
  public function setTicket($ticket)
  {
    $this->ticket = $ticket;

    return $this;
  }

  /**
   * Get ticket
   *
   * @return Ticket
   */
  public function getTicket()
  {
    return $this->ticket;
  }

  /**
   * Set filename
   *
   * @param string $filename
   * @return TicketFile
   */
  public function setFilename($filename)
  {
    $this->filename = $filename;

    return $this;
  }

  /**
   * Get filename
   *
   * @return string
   */
  public function getFilename()
  {
    return $this->filename;
  }
}
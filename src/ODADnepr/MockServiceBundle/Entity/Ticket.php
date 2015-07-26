<?php

namespace ODADnepr\MockServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * Ticket
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Ticket
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var User
     *
     * @OneToOne(targetEntity="User")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var Address
     *
     * @OneToOne(targetEntity="Address")
     * @JoinColumn(name="address_id", referencedColumnName="id")
     */
    private $address;

    /**
     * @var integer
     *
     * @ORM\Column(name="manager", type="integer")
     */
    private $manager;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text")
     */
    private $text;

    /**
     * @var integer
     *
     * @ORM\Column(name="created_date", type="integer")
     */
    private $created_date;

    /**
     * @var integer
     *
     * @ORM\Column(name="completion_date", type="integer")
     */
    private $completion_date;

    /**
     * @var integer
     *
     * @ORM\Column(name="state", type="integer")
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="ticketnumber", type="string", length=255)
     */
    private $ticketnumber;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string")
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text")
     */
    private $comment;


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
     * Set user
     *
     * @param \stdClass $user
     * @return Ticket
     */
    public function setUser(\stdClass $user)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \stdClass
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set address
     *
     * @param \stdClass $address
     * @return Ticket
     */
    public function setAddress(\stdClass $address)
    {
        $this->address = $address;
    
        return $this;
    }

    /**
     * Get address
     *
     * @return \stdClass
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set manager
     *
     * @param integer $manager
     * @return Ticket
     */
    public function setManager($manager)
    {
        $this->manager = $manager;
    
        return $this;
    }

    /**
     * Get manager
     *
     * @return integer
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Ticket
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Ticket
     */
    public function setText($text)
    {
        $this->text = $text;
    
        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set created_date
     *
     * @param integer $createdDate
     * @return Ticket
     */
    public function setCreatedDate($createdDate)
    {
        $this->created_date = $createdDate;
    
        return $this;
    }

    /**
     * Get created_date
     *
     * @return integer
     */
    public function getCreatedDate()
    {
        return $this->created_date;
    }

    /**
     * Set completion_date
     *
     * @param integer $completionDate
     * @return Ticket
     */
    public function setCompletionDate($completionDate)
    {
        $this->completion_date = $completionDate;
    
        return $this;
    }

    /**
     * Get completion_date
     *
     * @return integer
     */
    public function getCompletionDate()
    {
        return $this->completion_date;
    }

    /**
     * Set state
     *
     * @param integer $state
     * @return Ticket
     */
    public function setState($state)
    {
        $this->state = $state;
    
        return $this;
    }

    /**
     * Get state
     *
     * @return integer
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set ticketnumber
     *
     * @param string $ticketnumber
     * @return Ticket
     */
    public function setTicketnumber($ticketnumber)
    {
        $this->ticketnumber = $ticketnumber;
    
        return $this;
    }

    /**
     * Get ticketnumber
     *
     * @return string 
     */
    public function getTicketnumber()
    {
        return $this->ticketnumber;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return Ticket
     */
    public function setImage($image)
    {
        $this->image = $image;
    
        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return Ticket
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    
        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }
}

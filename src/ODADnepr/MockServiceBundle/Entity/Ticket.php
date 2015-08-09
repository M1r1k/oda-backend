<?php

namespace ODADnepr\MockServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\NotBlank()
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @var Address
     * @Assert\NotBlank()
     * @ManyToOne(targetEntity="Address")
     * @JoinColumn(name="address_id", referencedColumnName="id")
     */
    private $address;

    /**
     * @var Manager
     *
     * @ManyToOne(targetEntity="Manager")
     * @JoinColumn(name="manager_id", referencedColumnName="id", nullable=true)
     */
    private $manager;

    /**
     * @var TicketCategory
     * @Assert\NotBlank()
     * @ManyToOne(targetEntity="TicketCategory")
     * @JoinColumn(name="category_id", referencedColumnName="id", nullable=false)
     */
    private $category;

    /**
     * @var TicketType
     * @Assert\NotBlank()
     * @ManyToOne(targetEntity="TicketType")
     * @JoinColumn(name="type_id", referencedColumnName="id", nullable=false)
     */
    private $type;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="body", type="text")
     */
    private $body;

    /**
     * @var integer
     * @ORM\Column(name="created_date", type="integer")
     */
    private $created_date;

    /**
     * @var integer
     * @ORM\Column(name="completed_date", type="integer", nullable=true)
     */
    private $completed_date;

    /**
     * @var TicketState
     *
     * @ManyToOne(targetEntity="TicketState")
     * @JoinColumn(name="state_id", referencedColumnName="id", nullable=false)
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="ticket_id", type="string", length=255, nullable=false)
     */
    private $ticket_id;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", nullable=true)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
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
     * @param User $user
     * @return Ticket
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set address
     *
     * @param Address $address
     * @return Ticket
     */
    public function setAddress(Address $address)
    {
        $this->address = $address;
    
        return $this;
    }

    /**
     * Get address
     *
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set manager
     *
     * @param Manager $manager
     * @return Ticket
     */
    public function setManager(Manager $manager)
    {
        $this->manager = $manager;
    
        return $this;
    }

    /**
     * Get manager
     *
     * @return Manager
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * Set category
     *
     * @param TicketCategory $category
     * @return Ticket
     */
    public function setCategory(TicketCategory $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return TicketCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set type
     *
     * @param TicketType $type
     * @return Ticket
     */
    public function setType(TicketType $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return TicketType
     */
    public function getType()
    {
        return $this->type;
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
     * @param string $body
     * @return Ticket
     */
    public function setBody($body)
    {
        $this->body = $body;
    
        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getBody()
    {
        return $this->body;
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
    public function setCompletedDate($completionDate)
    {
        $this->completed_date = $completionDate;
    
        return $this;
    }

    /**
     * Get completion_date
     *
     * @return integer
     */
    public function getCompletedDate()
    {
        return $this->completed_date;
    }

    /**
     * Set state
     *
     * @param TicketState $state
     * @return Ticket
     */
    public function setState(TicketState $state)
    {
        $this->state = $state;
    
        return $this;
    }

    /**
     * Get state
     *
     * @return TicketState
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set ticketnumber
     *
     * @param string $ticket_id
     * @return Ticket
     */
    public function setTicketId($ticket_id)
    {
        $this->ticket_id = $ticket_id;
    
        return $this;
    }

    /**
     * Get ticketnumber
     *
     * @return string 
     */
    public function getTicketId()
    {
        return $this->ticket_id;
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

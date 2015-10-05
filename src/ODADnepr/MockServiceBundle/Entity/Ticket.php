<?php

namespace ODADnepr\MockServiceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

use JMS\Serializer\Annotation\Exclude;

use ODADnepr\MockServiceBundle\Service\GeoInterface;

/**
 * Ticket
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Ticket implements GeoInterface
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
     * @var integer
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     * @Exclude
     */
    private $user_id;

    /**
     * @var Address
     * @ManyToOne(targetEntity="Address")
     * @JoinColumn(name="address_id", referencedColumnName="id", nullable=true)
     */
    private $address;

    /**
     * @var GeoAddress
     * @ManyToOne(targetEntity="GeoAddress", cascade={"persist"})
     * @JoinColumn(name="geo_address_id", referencedColumnName="id", nullable=true)
     */
    private $geo_address;

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
     * @ORM\Column(name="start_date", type="integer", nullable=true)
     */
    private $start_date;

    /**
     * @var integer
     * @ORM\Column(name="updated_date", type="integer", nullable=true)
     */
    private $updated_date;

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
     * @var integer
     * @ORM\Column(name="state_id", type="integer", nullable=false)
     * @Exclude
     */
    private $state_id;

    /**
     * @var string
     *
     * @ORM\Column(name="ticket_id", type="string", length=255, nullable=false)
     */
    private $ticket_id;

    /**
     * @ORM\OneToMany(targetEntity="TicketFile", mappedBy="ticket")
     */
    private $files;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;

    /**
     * @var integer
     *
     * @ORM\OneToMany(targetEntity="TicketLike", mappedBy="ticket", cascade={"persist"})
     * @Exclude
     */
    private $likes;

    /**
     * @var integer
     *
     * @ORM\Column(name="likes_сounter", type="integer", nullable=false)
     */
    private $likesCounter = 0;

    public function __construct() {
        $this->features = new ArrayCollection();
        $this->likes = new ArrayCollection();
    }

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
     * Get user id
     *
     * @return UserId
     */
    public function getUserId()
    {
        return $this->user_id;
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
     * Set address
     *
     * @param GeoAddress $geo_address
     * @return Ticket
     */
    public function setGeoAddress(GeoAddress $geo_address)
    {
        $this->geo_address = $geo_address;

        return $this;
    }

    /**
     * Get geo address
     *
     * @return GeoAddress
     */
    public function getGeoAddress()
    {
        return $this->geo_address;
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

    /**
     * @return int
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * @param int $start_date
     * @return Ticket
     */
    public function setStartDate($start_date)
    {
        $this->start_date = $start_date;

        return $this;
    }
    /**
     * Add images.
     *
     * @param TicketFile $file
     */
    public function addFile(TicketFile $file)
    {
        $this->files[] = $file;

        $file->setTicket($this);
    }

    /**
     * @return Collection
     */
    public function getFiles() {
        return $this->files;
    }

    /**
     * @return int
     */
    public function getUpdatedDate() {
        return $this->updated_date;
    }

    /**
     * Set updated_date
     *
     * @param integer $updatedDate
     * @return Ticket
     */
    public function setUpdatedDate($updatedDate)
    {
        $this->updated_date = $updatedDate;

        return $this;
    }

    /**
     * Add likes
     *
     * @param \ODADnepr\MockServiceBundle\Entity\TicketLike $likes
     * @return Ticket
     */
    public function addLike(\ODADnepr\MockServiceBundle\Entity\TicketLike $likes)
    {
        $this->likes[] = $likes;

        $this->likesCounter = $this->likes->count();

        return $this;
    }

    /**
     * Remove likes
     *
     * @param \ODADnepr\MockServiceBundle\Entity\TicketLike $likes
     */
    public function removeLike(\ODADnepr\MockServiceBundle\Entity\TicketLike $likes)
    {
        $this->likes->removeElement($likes);
    }

    /**
     * Get likes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLikes()
    {
        return $this->likes;
    }

    public function setStateId($state_id, $states = array()) {
        $this->state_id = $state_id;
    }

    public function validate(ExecutionContextInterface $context) {
        if ($this->getGeoAddress() && !$this->getGeoAddress()->getAddress()) {
            if (!$this->areCoordinatesSetted()) {
                return $context->buildViolation('Latitude and Longitude must be setted if address doesnt exist')
                    ->atPath('geo_address')
                    ->addViolation();
            }
        }
    }

    public function areCoordinatesSetted() {
        return $this->getGeoAddress()->getLatitude() && $this->getGeoAddress()->getLongitude();
    }
}

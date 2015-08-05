<?php

namespace ODADnepr\MockServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Facilities
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Facilities
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
     * @var string(nullable=false
     *
     * @ORM\Column(name="name", type="string(nullable=false")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="unique=false)", type="string")
     */
    private $unique=false);


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
     * Set name
     *
     * @param \string(nullable=false $name
     * @return Facilities
     */
    public function setName(\string(nullable=false $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return \string(nullable=false 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set unique=false)
     *
     * @param string $unique=false)
     * @return Facilities
     */
    public function setUnique=false)($unique=false))
    {
        $this->unique=false) = $unique=false);

        return $this;
    }

    /**
     * Get unique=false)
     *
     * @return string 
     */
    public function getUnique=false)()
    {
        return $this->unique=false);
    }
}

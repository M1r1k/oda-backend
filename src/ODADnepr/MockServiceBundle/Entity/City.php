<?php

namespace ODADnepr\MockServiceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Groups;

/**
 * City
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class City
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="ru_name", type="string", length=255)
     */
    private $ru_name;

    /**
     * @Groups({"with_district"})
     * @ManyToOne(targetEntity="District", inversedBy="cities")
     * @JoinColumn(name="district_id", referencedColumnName="id")
     */
    private $district;

    /**
     * @var string
     * @Exclude
     * @OneToMany(targetEntity="Street", mappedBy="city")
     */
    private $streets;

    public function __construct() {
      $this->streets = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return City
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set ru name
     *
     * @param string $ru_name
     * @return District
     */
    public function setRuName($ru_name) {
        $this->ru_name = $ru_name;
        return $this;
    }

    /**
     * Get ru_name
     *
     * @return string
     */
    public function getRuName() {
        return $this->ru_name;
    }

    /**
     * Set district
     *
     * @param integer $district
     * @return City
     */
    public function setDistrict($district)
    {
        $this->district = $district;

        return $this;
    }

    /**
     * Get district
     *
     * @return integer
     */
    public function getDistrict()
    {
        return $this->district;
    }
}

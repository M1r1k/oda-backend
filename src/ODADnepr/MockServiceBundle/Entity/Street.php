<?php

namespace ODADnepr\MockServiceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use JMS\Serializer\Annotation\Exclude;

/**
 * Street
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Street
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
     * @Exclude
     * @ManyToOne(targetEntity="City", inversedBy="streets")
     * @JoinColumn(name="city_id", referencedColumnName="id")
     */
    private $city;

    /**
     * @var string
     * @Exclude
     * @OneToMany(targetEntity="House", mappedBy="street")
     */
    private $houses;

    /**
     * @var StreetType
     * @ManyToOne(targetEntity="StreetType")
     * @JoinColumn(name="street_type_id", referencedColumnName="id", nullable=true)
     */
    private $streetType;

    /**
     * @var CityDistrict
     * @ManyToOne(targetEntity="CityDistrict")
     * @JoinColumn(name="city_district_id", referencedColumnName="id", nullable=true)
     */
    private $cityDistrict;

    public function __construct() {
      $this->houses = new ArrayCollection();
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
     * @return Street
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
     * Set city
     *
     * @param integer $city
     * @return Street
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return integer
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getHouses() {
        return $this->houses;
    }

    /**
     * @param string $houses
     * @return Street
     */
    public function setHouses($houses) {
        $this->houses = $houses;
        return $this;
    }

    /**
     * @return StreetType
     */
    public function getStreetType() {
        return $this->streetType;
    }

    /**
     * @param StreetType $streetType
     * @return Street
     */
    public function setStreetType($streetType) {
        $this->streetType = $streetType;
        return $this;
    }

    /**
     * @return CityDistrict
     */
    public function getCityDistrict() {
        return $this->cityDistrict;
    }

    /**
     * @param CityDistrict $cityDistrict
     * @return Street
     */
    public function setCityDistrict($cityDistrict) {
        $this->cityDistrict = $cityDistrict;
        return $this;
    }
}

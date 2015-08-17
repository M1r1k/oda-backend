<?php

namespace ODADnepr\MockServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity()
 * @UniqueEntity("email")
 */
class User implements SecurityUserInterface
{
    const GENDER_MALE = 'male';
    const GENDER_FEMALE = 'female';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Top description.
     *
     * @var string
     *
     * bottom description
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="first_name", type="string", length=255)
     */
    private $first_name;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="last_name", type="string", length=255)
     */
    private $last_name;

     /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="middle_name", type="string", length=255)
     */
    private $middle_name;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Assert\Length(min=3)
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var integer
     * @Assert\NotBlank
     * @ORM\Column(name="birthday", type="integer")
     */
  // @todo remove
    private $birthday = 1438793276;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", nullable=true)
     */
    private $image;

    /**
     * @var Facilities
     * @Assert\NotBlank()
     * @ManyToOne(targetEntity="Facilities")
     * @JoinColumn(name="facilities_id", referencedColumnName="id", nullable=true)
     */
    private $facilities;

    /**
     * @var SocialCondition
     * @Assert\NotBlank()
     * @ManyToOne(targetEntity="SocialCondition")
     * @JoinColumn(name="social_condition_id", referencedColumnName="id", nullable=true)
     */
    private $socialCondition;

    /**
     * @var string
     * @Assert\Choice(choices = {"male", "female"}, message = "Choose a valid gender.")
     * @ORM\Column(name="gender", type="string", length=255, nullable=true)
     */
    private $gender;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @var Address
     * @Assert\NotBlank()
     * @ManyToOne(targetEntity="Address", cascade={"persist"})
     * @JoinColumn(name="address_id", referencedColumnName="id")
     */
    private $address;

    /**
     * Encrypted password. Must be persisted.
     *
     * @var string
     * @ORM\Column(name="password", type="string", length=255)
     */
    protected $password;

  /**
   * @return string
   */
  public function getPassword() {
    return $this->password;
  }

  /**
   * @param string $password
   */
  public function setPassword($password) {
    $this->password = $password;
  }

  /**
   * @return string
   */
  public function getPlainPassword() {
    return $this->plainPassword;
  }

  /**
   * @param string $plainPassword
   */
  public function setPlainPassword($plainPassword) {
    $this->plainPassword = $plainPassword;
  }

    /**
     * Plain password. Used for model validation. Must not be persisted.
     *
     * @var string
     */
    protected $plainPassword;

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
     * Set first_name
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;
    
        return $this;
    }

    /**
     * Get first_name
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set last_name
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->last_name = $lastName;
    
        return $this;
    }

    /**
     * Get last_name
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @return string
     */
    public function getMiddleName() {
        return $this->middle_name;
    }

    /**
     * @param string $middle_name
     * @return User
     */
    public function setMiddleName($middle_name) {
        $this->middle_name = $middle_name;
        return $this;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set facilities
     *
     * @param Facilities $facilities
     * @return User
     */
    public function setFacilities(Facilities $facilities)
    {
        $this->facilities = $facilities;

        return $this;
    }

    /**
     * Get facilities
     *
     * @return Facilities
     */
    public function getFacilities()
    {
        return $this->facilities;
    }

    /**
     * Set social condition
     *
     * @param SocialCondition $socialCondition
     * @return User
     */
    public function setSocialCondition(SocialCondition $socialCondition)
    {
        $this->socialCondition = $socialCondition;

        return $this;
    }

    /**
     * Get social condition
     *
     * @return SocialCondition
     */
    public function getSocialCondition()
    {
        return $this->socialCondition;
    }

    /**
     * Set birthday
     *
     * @param integer $birthday
     * @return User
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    
        return $this;
    }

    /**
     * Get birthday
     *
     * @return integer 
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return User
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
     * Set phone
     *
     * @param string $phone
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    
        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set address
     *
     * @param Address $address
     * @return User
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
   * Returns the roles granted to the user.
   *
   * <code>
   * public function getRoles()
   * {
   *     return array('ROLE_USER');
   * }
   * </code>
   *
   * Alternatively, the roles might be stored on a ``roles`` property,
   * and populated in any number of different ways when the user object
   * is created.
   *
   * @return Role[] The user roles
   */
  public function getRoles() {
    return array('ROLE_USER');
  }

  /**
   * Returns the salt that was originally used to encode the password.
   *
   * This can return null if the password was not encoded using a salt.
   *
   * @return string|null The salt
   */
  public function getSalt() {
    return '';
  }

  /**
   * Returns the username used to authenticate the user.
   *
   * @return string The username
   */
  public function getUsername() {
    return $this->email;
  }

  /**
   * Removes sensitive data from the user.
   *
   * This is important if, at any given point, sensitive information like
   * the plain-text password is stored on this object.
   */
  public function eraseCredentials() {
    $this->plainPassword = null;
  }
}

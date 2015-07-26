<?php
namespace ODADnepr\MockServiceBundle\Security;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;
use ODADnepr\MockServiceBundle\Entity\User;
class UserProvider implements UserProviderInterface
{
  /**
   * @var \Doctrine\Common\Persistence\ObjectRepository
   */
  protected $userManager;
  /**
   * Constructor.
   *
   * @param ObjectManager $entityManager
   */
  public function __construct(ObjectManager $entityManager)
  {
    $this->userManager = $entityManager->getRepository('ODADneprMockServiceBundle:User');
  }
  /**
   * {@inheritDoc}
   */
  public function loadUserByUsername($username)
  {
    $user = $this->findUser($username);
    if (!$user) {
      throw new UsernameNotFoundException(sprintf('Email "%s" does not exist.', $username));
    }
    return $user;
  }
  /**
   * {@inheritDoc}
   */
  public function refreshUser(SecurityUserInterface $user)
  {
    if (!$user instanceof User) {
      throw new UnsupportedUserException(sprintf('Expected an instance of FOS\UserBundle\Model\User, but got "%s".', get_class($user)));
    }
    if (null === $reloadedUser = $this->userManager->findOneBy(array('id' => $user->getId()))) {
      throw new UsernameNotFoundException(sprintf('User with ID "%d" could not be reloaded.', $user->getId()));
    }
    return $reloadedUser;
  }
  /**
   * {@inheritDoc}
   */
  public function supportsClass($class)
  {
    $userClass = $this->userManager->getClass();
    return $userClass === $class || is_subclass_of($class, $userClass);
  }
  /**
   * Finds a user by username.
   *
   * This method is meant to be an extension point for child classes.
   *
   * @param string $username
   *
   * @return UserInterface|null
   */
  protected function findUser($username)
  {
    return $this->userManager->findOneBy(array('email' => $username));
  }
}

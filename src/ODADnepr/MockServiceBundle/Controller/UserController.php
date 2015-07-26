<?php

namespace ODADnepr\MockServiceBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use ODADnepr\MockServiceBundle\Entity\City;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ODADnepr\MockServiceBundle\Entity\User;
use ODADnepr\MockServiceBundle\Entity\District;
use ODADnepr\MockServiceBundle\Entity\Address;
use ODADnepr\MockServiceBundle\Entity\Street;
use ODADnepr\MockServiceBundle\Entity\House;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UserController extends FOSRestController
{

  /**
   * @var \Doctrine\Common\Persistence\ObjectRepository
   */
  protected $userRepository;

  /**
   * @var \Doctrine\Common\Persistence\ObjectManager
   */
  protected $entityManager;
    public function manualConstruct() {
      $this->entityManager = $this->getDoctrine()->getManager();
      $this->userRepository = $this->entityManager->getRepository('ODADneprMockServiceBundle:User');
    }
  /**
   * @Route("/rest/v1/generate/user")
   * @Method({"POST"})
   */
  public function generateDevContentAction(Request $request) {
    $this->manualConstruct();
    $user_object = json_decode($request->getContent());
    $user= $this->saveUserWithRelations($user_object);

    return $user;
  }
    /**
     * @Route("/rest/v1/users")
     * @Method({"GET"})
     */
    public function indexAction()
    {
      $this->manualConstruct();

      $users = $this->userRepository->findAll();
      return $users;
    }

  /**
   * @Route("/rest/v1/user/{id}")
   * @Method({"GET"})
   */
  public function getAction($id) {
    $this->manualConstruct();

    return $this->userRepository->find($id);
  }

  /**
   * @Route("/rest/v1/user/{id}")
   * @Method({"DELETE"})
   */
  public function deleteAction($id) {
    $this->manualConstruct();
    $user = $this->userRepository->find($id);
    $this->entityManager->remove($user);
    return ['status message' => 'woohoo!'];
  }

  /**
   * @Route("/rest/v1/user")
   * @Method({"POST"})
   */
  public function postAction(Request $request) {
    $user_object = json_decode($request->getContent());
    $user = $this->saveUserWithRelations($user_object);

    return $user;
  }

  /**
   * @Route("/rest/v1/user")
   * @Method({"PUT"})
   */
  public function putAction(Request $request) {
    $user_object = json_decode($request->getContent());
    $user = $this->saveUserWithRelations($user_object, true);

    return $user;
  }

  protected function saveUserWithRelations(\stdClass $userObject, $update = false) {
    $address = $this->setAddress($userObject->address);
    if ($update && ($user = $this->userRepository->find($userObject))) {

    }
    else {
      $user = new User();
    }
    $user->setAddress($address);
    $user->setBirthday($userObject->birthday);
    $user->setEmail($userObject->email);
    $user->setFirstName($userObject->first_name);
    $user->setLastName($userObject->last_name);
    $user->setImage($userObject->image);
    $user->setPhone($userObject->phone);
    $user->setPassword($userObject->password);
    $validator = $this->get('validator');
    $errors = $validator->validate($user);
    if (empty($errors)) {
      $this->entityManager->persist($user);
      $this->entityManager->flush();
      return $user;
    }
    $serializer = $this->get('serializer');
var_dump($serializer->toArray($errors));
    throw new BadRequestHttpException(json_encode($serializer->toArray($errors)));
  }

  protected function setAddress(\stdClass $address) {
    $repo = $this->entityManager->getRepository('ODADneprMockServiceBundle:Address');
    if (!($address_entity = $repo->find($address->id))) {
      $district = $this->setDistrict($address->district);
      $city = $this->setCity($address->city, $district);
      $street = $this->setStreet($address->street, $city);
      $house = $this->setHouse($address->house, $street);
      $address_entity = new Address();
      $address_entity->setStreet($street);
      $address_entity->setCity($city);
      $address_entity->setDistrict($district);
      $address_entity->setHouse($house);
      $address_entity->setFlat($address->flat);
      $this->entityManager->persist($address_entity);
      $this->entityManager->flush();
    }

    return $address_entity;
  }

  protected function setDistrict(\stdClass $district_object) {
    $repo = $this->entityManager->getRepository('ODADneprMockServiceBundle:District');
    if (!($district = $repo->find($district_object->id))) {
      $district = new District();
      $district->setName($district_object->name);
      $this->entityManager->persist($district);
      $this->entityManager->flush();
    }

    return $district;
  }

  protected function setCity(\stdClass $city_object, District $district) {
    $repo = $this->entityManager->getRepository('ODADneprMockServiceBundle:City');
    if (!($city = $repo->find($city_object->id))) {
      $city = new City();
      $city->setName($city_object->name);
      $city->setDistrict($district);
      $this->entityManager->persist($city);
      $this->entityManager->flush();
    }

    return $city;
  }

  protected function setStreet(\stdClass $street_object, City $city) {
    $repo = $this->entityManager->getRepository('ODADneprMockServiceBundle:Street');
    if (!($street = $repo->find($street_object->id))) {
      $street = new Street();
      $street->setName($street_object->name);
      $street->setCity($city);
      $this->entityManager->persist($street);
      $this->entityManager->flush();
    }

    return $street;
  }

  protected function setHouse(\stdClass $house_object, Street $street) {
    $repo = $this->entityManager->getRepository('ODADneprMockServiceBundle:House');
    if (!($house = $repo->find($house_object->id))) {
      $house = new House();
      $house->setName($house_object->name);
      $house->setStreet($street);
      $this->entityManager->persist($house);
      $this->entityManager->flush();
    }

    return $house;
  }
}

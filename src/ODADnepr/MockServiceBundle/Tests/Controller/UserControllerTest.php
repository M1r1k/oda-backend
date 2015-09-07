<?php

namespace ODADnepr\MockServiceBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase as WebTestCase;
use ODADnepr\MockServiceBundle\Fixtures\Entity;
use JMS\Serializer\SerializerBuilder;

class UserControllerTest extends WebTestCase {

  private $fixtures;
  private $serializer;
  private $userData;

  function __construct() {
    parent::__construct();

    $fixtures = array(
      'ODADnepr\MockServiceBundle\DataFixtures\ORM\LoadDistrictData',
      'ODADnepr\MockServiceBundle\DataFixtures\ORM\LoadCityData',
      'ODADnepr\MockServiceBundle\DataFixtures\ORM\LoadCityDistrictData',
      'ODADnepr\MockServiceBundle\DataFixtures\ORM\LoadStreetData',
      'ODADnepr\MockServiceBundle\DataFixtures\ORM\LoadHouseData',
      'ODADnepr\MockServiceBundle\DataFixtures\ORM\LoadAddressData'
    );

    $this->fixtures = $this->loadFixtures($fixtures)->getReferenceRepository();

    $serializer = SerializerBuilder::create()->build();

    $this->userData = array(
      "first_name" => "Федя",
      "password" => "new-password",
      "last_name" => "Поплавок",
      "middle_name" => "Yo yo",
      "email" => "ex@ex.com",
      "birthday" => 1437816988,
      "phone" => "+142322443",
      "address" => json_decode($serializer->serialize($this->fixtures->getReference('address'), 'json'), true)
    );
  }

  public function testCreateUserAction()
  {
    $response = $this->createUser($this->userData);

    $this->assertJsonResponse($response, 200);
    $this->assertTrue(isset(json_decode($response->getContent())->token));
    $this->assertTrue(isset(json_decode($response->getContent())->user));
  }

  public function testCreateUserActionBadParams() {
    $badUser = $this->userData;
    unset($badUser["first_name"]);

    $response = $this->createUser($badUser);
    $this->assertJsonResponse($response, 400);
  }

  public function testAuth() {
    $client = static::createClient();

    $client->request(
      'POST',
      '/rest/v1/user-auth-check',
      array(),
      array(),
      array('Content-Type' => 'application/json'),
      json_encode(array(
        'username' => $this->userData['email'],
        'password' => $this->userData['password']
      ))
    );

    $response = $client->getResponse();
    $this->assertJsonResponse($response, 200);
  }

  public function testBadAuth() {
    $client = static::createClient();

    $client->request(
      'POST',
      '/rest/v1/user-auth-check',
      array(),
      array(),
      array('Content-Type' => 'application/json'),
      json_encode(array(
        'username' => $this->userData['email'],
        'password' => 'bad pass'
      ))
    );

    $response = $client->getResponse();

    $this->assertJsonResponse($response, 401);
  }

  private function createUser($user) {
    $client = static::createClient();

    $client->request(
      'POST',
      '/rest/v1/user-register?_format=json',
      array(),
      array(),
      array('Content-Type' => 'application/json'),
      json_encode($user)
    );

    return $client->getResponse();
  }

  protected function assertJsonResponse($response, $statusCode = 200, $checkValidJson =  true, $contentType = 'application/json')
  {
    $this->assertEquals(
      $statusCode, $response->getStatusCode(),
      $response->getContent()
    );
    $this->assertTrue(
      $response->headers->contains('Content-Type', $contentType),
      $response->headers
    );

    if ($checkValidJson) {
      $decode = json_decode($response->getContent());
      $this->assertTrue(($decode != null && $decode != false),
        'is response valid json: [' . $response->getContent() . ']'
      );
    }
  }
}
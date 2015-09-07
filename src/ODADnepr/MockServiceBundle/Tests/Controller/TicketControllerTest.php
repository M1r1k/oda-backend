<?php

namespace ODADnepr\MockServiceBundle\Tests\Controller;

use Doctrine\Common\DataFixtures\ReferenceRepository;
use JMS\Serializer\SerializerBuilder;
use Liip\FunctionalTestBundle\Test\WebTestCase as WebTestCase;

use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\BrowserKit\Cookie;

Class TicketControllerTest extends WebTestCase {
  /** @var Client  */
  private $client = null;
  /** @var ReferenceRepository */
  private $fixtures;

  function __construct() {
    parent::__construct();

    $fixtures = array(
      'ODADnepr\MockServiceBundle\DataFixtures\ORM\LoadDistrictData',
      'ODADnepr\MockServiceBundle\DataFixtures\ORM\LoadCityData',
      'ODADnepr\MockServiceBundle\DataFixtures\ORM\LoadCityDistrictData',
      'ODADnepr\MockServiceBundle\DataFixtures\ORM\LoadStreetData',
      'ODADnepr\MockServiceBundle\DataFixtures\ORM\LoadHouseData',
      'ODADnepr\MockServiceBundle\DataFixtures\ORM\LoadAddressData',
      'ODADnepr\MockServiceBundle\DataFixtures\ORM\LoadUserData'
    );

    $this->fixtures = $this->loadFixtures($fixtures)->getReferenceRepository();
  }

  public function setUp()
  {
    $this->client = static::createClient();
  }

  public function testCreateTicket()
  {
    $this->logIn();

    $this->client->request('POST', '/rest/v1/ticket', array(), array(),
      array('Content-Type' => 'application/json'),
      json_encode(array())
    );

    $response = $this->client->getResponse();
    print_r($this->client->getResponse()->getContent());
  }

  private function logIn() {
    $jwt_manager = $this->client->getContainer()->get('lexik_jwt_authentication.jwt_manager');
    $user = $this->fixtures->getReference('user');
    $token = $jwt_manager->create($user);
    $this->client->setServerParameter('Authorization', $token);
  }
}
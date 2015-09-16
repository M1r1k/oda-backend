<?php

namespace ODADnepr\MockServiceBundle\Tests\Controller;

use ODADnepr\MockServiceBundle\Fixtures\Entity;
use JMS\Serializer\SerializerBuilder;
use Liip\FunctionalTestBundle\Test\WebTestCase as WebTestCase;

Class TicketControllerTest extends WebTestCase {
  use \ODADnepr\MockServiceBundle\Tests\Helpers;

  private $fixtures;

  public function testCreateTicketAction(){
    $client = $this->createAuthenticatedClient();
    $client->request(
      'POST',
      '/rest/v1/ticket?_format=json',
      array(),
      array(),
      array('Content-Type' => 'application/json'),
      json_encode($this->ticketData())
    );

    $response = $client->getResponse();
    $this->assertJsonResponse($response, 200);

    $json = json_decode($response->getContent());

    $this->assertTrue(isset($json->longitude));
    $this->assertTrue(isset($json->latitude));
  }

  public function testCreateTicketWithoutAddress() {
    $data = $this->ticketData();

    unset($data['address']);
    $data['longitude'] = '35.068338';
    $data['latitude'] = '48.452295';

    $client = $this->createAuthenticatedClient();
    $client->request(
      'POST',
      '/rest/v1/ticket?_format=json',
      array(),
      array(),
      array('Content-Type' => 'application/json'),
      json_encode($data)
    );

    $response = $client->getResponse();
    $this->assertJsonResponse($response, 200);
  }

  public function testCreateTicketBadCoordinates() {
    $data = $this->ticketData();

    unset($data['address']);
    $data['longitude'] = '35.017387';
    $data['latitude'] = '48.465154';

    $client = $this->createAuthenticatedClient();
    $client->request(
      'POST',
      '/rest/v1/ticket?_format=json',
      array(),
      array(),
      array('Content-Type' => 'application/json'),
      json_encode($data)
    );

    $response = $client->getResponse();
    $this->assertJsonResponse($response, 400);
  }

  public function testCreatTickeNotAuthorized() {
    $client = static::createClient();

    $client->request(
      'POST',
      '/rest/v1/ticket?_format=json',
      array(),
      array(),
      array('Content-Type' => 'application/json'),
      json_encode($this->ticketData())
    );

    $response = $client->getResponse();
    $this->assertJsonResponse($response, 401);
  }

  public function testGetTicketsListAction(){
    $client = static::createClient();

    $client->request('GET', '/rest/v1/tickets?_format=json');
    $response = $client->getResponse();

    $this->assertJsonResponse($response, 200);
  }

  public function testGetTicketsListByCategoryAction() {
    $categoryId = 2;

    $client = static::createClient();

    $client->request('GET', "/rest/v1/tickets?category=$categoryId&_format=json");
    $response = $client->getResponse();

    $this->assertJsonResponse($response, 200);

    foreach (json_decode($response->getContent()) as $ticket) {
      $this->assertTrue($ticket->category->id == $categoryId);
    }
  }

  public function testGetTicketsListActionByState() {
    $stateId = 1;

    $client = static::createClient();

    $client->request('GET', "/rest/v1/tickets?state=$stateId&_format=json");
    $response = $client->getResponse();

    $this->assertJsonResponse($response, 200);

    foreach (json_decode($response->getContent()) as $ticket) {
      $this->assertTrue($ticket->state->id == $stateId);
    }
  }

  public function testGetTicketsListActionByTitle(){
    $title = 'fst';

    $client = static::createClient();

    $client->request('GET', "/rest/v1/tickets?title=$title&_format=json");
    $response = $client->getResponse();

    $this->assertJsonResponse($response, 200);
    $json = json_decode($response->getContent(), true);

    $this->assertEquals(count($json), 1);
    $this->assertEquals(count($json[0]['id']), 1);
  }

  private function ticketData(){
    $serializer = SerializerBuilder::create()->build();

    return array(
      "title" => "Tiket title",
      "body" => "Tiket body text",
      "created_date" => time(),
      "ticket_id" => "some ticket id",
      "address" => json_decode($serializer->serialize($this->getFixtures()->getReference('address'), 'json'), true),
      "user" => json_decode($serializer->serialize($this->getFixtures()->getReference('user'), 'json'), true),
      "category" => json_decode($serializer->serialize($this->getFixtures()->getReference('ticketCategory'), 'json'), true),
      "type" => json_decode($serializer->serialize($this->getFixtures()->getReference('ticketType'), 'json'), true),
      "state" => json_decode($serializer->serialize($this->getFixtures()->getReference('ticketState'), 'json'), true)
    );
  }

  private function getFixtures() {
    if ($this->fixtures){
      return $this->fixtures;
    }

    $this->fixtures = $this->loadFixtures(array(
      'ODADnepr\MockServiceBundle\DataFixtures\ORM\LoadDistrictData',
      'ODADnepr\MockServiceBundle\DataFixtures\ORM\LoadCityData',
      'ODADnepr\MockServiceBundle\DataFixtures\ORM\LoadCityDistrictData',
      'ODADnepr\MockServiceBundle\DataFixtures\ORM\LoadStreetData',
      'ODADnepr\MockServiceBundle\DataFixtures\ORM\LoadHouseData',
      'ODADnepr\MockServiceBundle\DataFixtures\ORM\LoadAddressData',
      'ODADnepr\MockServiceBundle\DataFixtures\ORM\LoadUserData',
      'ODADnepr\MockServiceBundle\DataFixtures\ORM\LoadTicketCategoryData',
      'ODADnepr\MockServiceBundle\DataFixtures\ORM\LoadTicketStateData',
      'ODADnepr\MockServiceBundle\DataFixtures\ORM\LoadTicketTypeData',
      'ODADnepr\MockServiceBundle\DataFixtures\ORM\LoadManyTicketsData',
    ))->getReferenceRepository();

    return $this->fixtures;
  }

  private function createAuthenticatedClient() {
    $client = static::createClient();
    $client->request(
      'POST',
      '/rest/v1/user-auth-check',
      array(),
      array(),
      array(),
      json_encode(array(
        'username' => $this->getFixtures()->getReference('user')->getEmail(),
        'password' => $this->getFixtures()->getReference('user')->getPassword(),
      ))
    );

    $data = json_decode($client->getResponse()->getContent(), true);

    $authed = static::createClient();
    $authed->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

    return $authed;
  }
}
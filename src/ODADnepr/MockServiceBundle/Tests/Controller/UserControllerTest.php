<?php

namespace ODADnepr\MockServiceBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase as WebTestCase;

class UserControllerTest extends WebTestCase {

  private $userData;

  function __construct() {
    parent::__construct();

    $this->userData = array(
      'first_name' => 'TestName',
      'last_name' => 'TestLastName',
      'middle_name' => 'TestMiddleName',
      'email' => 'email@email.com',
      'birthday' => time(),
      'password' => 'password',
      'address' => array(
        'id' => 1,
        'flat' => '123B',
        'district' => array(
          'id' => 1,
          'name' => 'Dnipropetrovska'
        ),
        'city' => array(
          'id' => 1,
          'name' => 'Dnipropetrovsk'
        ),
        'street' => array(
          'id' => 1,
          'name' => 'Dnipropetrovsk'
        ),
        "house" => array(
          'id' => 1,
          'name' => 5
        )
      )
    );
  }

  public function testCreateUserAction()
  {
    $client = static::createClient();

    $client->request(
      'POST',
      '/rest/v1/user-register',
      array(),
      array(),
      array('Content-Type' => 'application/json'),
      json_encode($this->userData)
    );

    $response = $client->getResponse();

    // $user = $client->getContainer()->get('doctrine.orm.entity_manager')->getRepository('ODADnepr\MockServiceBundle\Entity\User')->find(12);

    var_dump($response->getContent());

    // $this->assertTrue($response->isSuccessful());
  }
}
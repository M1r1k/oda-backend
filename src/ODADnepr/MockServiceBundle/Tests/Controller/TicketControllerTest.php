<?php

namespace ODADnepr\MockServiceBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase as WebTestCase;
use ODADnepr\MockServiceBundle\Fixtures\Entity;

use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;
use Symfony\Component\BrowserKit\Cookie;

Class TicketControllerTest extends WebTestCase {
  private $client = null;

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
    $session = $this->client->getContainer()->get('session');

    $token = new JWTUserToken(array('ROLE_ADMIN'));
    $session->set('Authentication', $token);
    $session->save();

    $cookie = new Cookie($session->getName(), $session->getId());
    $this->client->getCookieJar()->set($cookie);
  }
}
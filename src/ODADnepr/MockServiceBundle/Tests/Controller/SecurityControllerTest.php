<?php

namespace ODADnepr\MockServiceBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLogin()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'rest/v1/user-auth');
    }

    public function testLogincheck()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'rest/v1/user-auth-check');
    }

}

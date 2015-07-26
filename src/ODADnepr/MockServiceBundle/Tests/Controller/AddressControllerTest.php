<?php

namespace ODADnepr\MockServiceBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddressControllerTest extends WebTestCase
{
    public function testGetdistricts()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/rest/v1/address/districts');
    }

    public function testGetcities()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/rest/v1/address/cities');
    }

    public function testGetstreets()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/rest/v1/address/streets');
    }

    public function testGethouses()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/rest/v1/address/houses');
    }

}

<?php

namespace Example\AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testApi()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/doctor');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertContains('Пирогов Иван Иванович', $data[0]['name']);
    }
}

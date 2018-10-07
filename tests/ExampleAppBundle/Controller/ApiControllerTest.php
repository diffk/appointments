<?php

namespace Example\AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/doctor');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        //$this->assertContains('Пирогов Иван Иванович', $crawler->filter('.element td')->text());
        $data = $client->getResponse()->getContent();

        //$this->assertContains('расписание доктора', $client->getResponse()->getContent());
    }
}

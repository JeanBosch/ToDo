<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase

{

    private KernelBrowser $client;

    public function setUp(): void

    {

        $this->client = static::createClient();

    }

    public function testIndex()
    {


        $urlgenerator = $this->client->getContainer()->get('router.default');
        $this->client->request('GET', $urlgenerator->generate('homepage'));

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}

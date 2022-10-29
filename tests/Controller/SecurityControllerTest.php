<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase

{

    private KernelBrowser $client;

    public function setUp(): void

    {

        $this->client = static::createClient();
        $this->userRepository = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class);
        //Pour tester un user normal, rempalcer l'adresse mail par cabau.matthieu@orange.fr
        //Pour tester un user normal, rempalcer l'adresse mail par cabau.matthieu@gmail.com
        $this->user = $this->userRepository->findOneBy(['email' => 'cabau.matthieu@orange.fr']);
        $this->urlGenerator = $this->client->getContainer()->get('router.default');
        $this->client->loginUser($this->user);

    }

    public function testLogin()
    {

        $this->client->request('GET', $this->urlGenerator->generate('login'));
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testLogout()
    {
            
            $this->client->request('GET', $this->urlGenerator->generate('app_logout'));
            $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }
}

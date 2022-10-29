<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Response;


class UserControllerTest extends WebTestCase

{

    private KernelBrowser $client;



    public function setUp(): void

    {

        $this->client = static::createClient();
        $this->userRepository = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class);
        $this->taskRepository = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Task::class);
        //Pour tester un user normal, rempalcer l'adresse mail par cabau.matthieu@orange.fr
        // Pour tester un user admin, rempalcer l'adresse mail par cabau.matthieu@gmail.com
        $this->user = $this->userRepository->findOneBy(['email' => 'cabau.matthieu@orange.fr']);
        $this->userEdit = $this->userRepository->findOneBy(['email' => 'cabau.matthieu@orange.fr']);
        $this->task = $this->taskRepository->findOneBy(['id' => '50']);
        $this->urlGenerator = $this->client->getContainer()->get('router.default');
        $this->client->loginUser($this->user);
    }

    public function testListUserAction()

    {
        $roleUser[] = $this->user->getRoles();
        $this->client->request('GET', $this->urlGenerator->generate( 'user_list'));
        if($roleUser[0][0] == "ROLE_ADMIN"){
            $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        }else{
            $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
        }
        
    }

    public function testCreateUserAction()
    {
        $roleUser[] = $this->user->getRoles();
        $this->client->request('GET', $this->urlGenerator->generate('user_create'));
        if($roleUser[0][0] == "ROLE_ADMIN"){
            $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        }else{
            $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
        }
    }

    public function testEditUserAction()
    {
        $roleUser[] = $this->user->getRoles();
        $this->client->request('GET', $this->urlGenerator->generate('user_edit', ['id' => $this->userEdit->getId()]));
        if($roleUser[0][0] == "ROLE_ADMIN"){
            $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        }else{
            $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
        }
    }

    
}

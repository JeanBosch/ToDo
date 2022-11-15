<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DomCrawler\Crawler;


class UserControllerTest extends WebTestCase

{

    private KernelBrowser $client;
    



    public function setUp(): void

    {

        $this->client = static::createClient();
        $this->client->followRedirects();
        
        $this->userRepository = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class);
        $this->taskRepository = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Task::class);
        //Pour tester un user normal, remplacer l'adresse mail par cabau.matthieu@orange.fr
        // Pour tester un user admin, remplacer l'adresse mail par cabau.matthieu@gmail.com
        $this->user = $this->userRepository->findOneBy(['email' => 'cabau.matthieu@gmail.com']);
        $this->userEdit = $this->userRepository->findOneBy(['id' => 3]);
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
        $crawler = $this->client->request('GET', $this->urlGenerator->generate('user_create'));
        if($roleUser[0][0] == "ROLE_ADMIN"){
            $form = $crawler->selectButton('Ajouter')->form();
            $form['user[username]'] = 'test';
            $form['user[email]'] = 'test@test.test';
            $form['user[password][first]'] = 'test';
            $form['user[password][second]'] = 'test';
            $form['user[roles]'] = 'ROLE_USER';
            $this->client->submit($form);
            $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        }else{
            $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
        }
    }

    public function testEditUserAction()
    {
        $roleUser[] = $this->user->getRoles();
        $crawler = $this->client->request('GET', $this->urlGenerator->generate('user_edit', ['id' => $this->userEdit->getId()]));
        if($roleUser[0][0] == "ROLE_ADMIN"){
            $form = $crawler->selectButton('Modifier')->form();
            $form['user[username]'] = 'testmodif';
            $form['user[password][first]'] = 'test';
            $form['user[password][second]'] = 'test';
            $form['user[email]'] = 'testmodif@test.test';
            $form['user[roles]'] = 'ROLE_USER';
            $this->client->submit($form);
            $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        }else{
            $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
        }
    }

 
        

    
}

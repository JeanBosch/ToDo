<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DomCrawler\Crawler;


class TaskControllerTest extends WebTestCase

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
        $this->task = $this->taskRepository->findOneBy(['id' => '101']);
        $this->urlGenerator = $this->client->getContainer()->get('router.default');
        $this->client->loginUser($this->user);
    }

    public function testListAction()

    {
        $tasks = $this->taskRepository->findBy(['isDone' => false]);
        $this->client->request('GET', $this->urlGenerator->generate( 'task_list', $tasks));
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testListFinished()
    {
        $tasks = $this->taskRepository->findBy(['isDone' => true]);
        $this->client->request('GET', $this->urlGenerator->generate('finished_tasks', $tasks));
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testShowAction()
    {

        
        $this->client->request('GET', $this->urlGenerator->generate('task_show', ['id' => $this->task->getId()]));
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testCreateAction()
    {
        $crawler = $this->client->request('GET', $this->urlGenerator->generate('task_create'));
        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'Test';
        $form['task[content]'] = 'Test';
        $this->client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testEditAction()
    {
        
        $roleUser[] = $this->user->getRoles();
        $crawler= $this->client->request('GET', $this->urlGenerator->generate('task_edit', ['id' => $this->task->getId()]));
        if($roleUser[0][0] == "ROLE_ADMIN" || $this->user->getId() == $this->task->getUser()->getId()){
            $form = $crawler->selectButton('Modifier')->form();
            $form['task[title]'] = 'Testmodifié';
            $form['task[content]'] = 'Testmodifié';
            $this->client->submit($form);
            $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        } else {
            
            $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
        }

}

    public function testToggleTaskAction()
    {
        $roleUser[] = $this->user->getRoles();

        
        if($roleUser[0][0] == "ROLE_ADMIN" || $this->user->getId() == $this->task->getUser()->getId()){
            if($this->task->IsDone() == false){
            $this->client->request('GET', $this->urlGenerator->generate('task_toggle', ['id' => $this->task->getId()]));              
            
            $this->assertSelectorTextContains('div.alert.alert-success', 'Superbe ! La tâche ' . $this->task->getTitle() .' a bien été marquée comme faite.');
            $this->assertResponseStatusCodeSame(Response::HTTP_OK);
            
        }
            else {
                $this->client->request('GET', $this->urlGenerator->generate('task_toggle', ['id' => $this->task->getId()]));
            
                $this->assertSelectorTextContains('div.alert.alert-success', 'Superbe ! La tâche ' . $this->task->getTitle() .' a bien été marquée comme faite.');
                $this->assertResponseStatusCodeSame(Response::HTTP_OK);
            }
     } else {
            $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
        }
        
    }

    public function testDeleteAction()
    {
        $roleUser[] = $this->user->getRoles();

        $this->client->request('GET', $this->urlGenerator->generate('task_delete', ['id' => $this->task->getId()]));
        if($roleUser[0][0] == "ROLE_ADMIN" || $this->user->getId() == $this->task->getUser()->getId()){
            
            $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        } else {
            
            $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
        }
    }
}

<?php
namespace App\Tests\Repository;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testAddTask()
    {
        $task = new Task();
        $task->setID(1000);
        $task->setTitle('Testrepository10');
        $task->setContent('Test');
        $task->setCreatedAt(new \DateTime());
        $task->setIsDone(false);
        $task->setUser($this->entityManager->getRepository(User::class)->find(1));
        $this->entityManager->getRepository(Task::class)->add($task, true);
        $this->assertNotNull($task);
        
        

    }

    public function testRemoveTask()
    {
        $task = $this->entityManager->getRepository(Task::class)->findOneBy(['id' => 112]);
        $this->entityManager->getRepository(Task::class)->remove($task, true);
        $this->assertNull($task->getID());
    }
}
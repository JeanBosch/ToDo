<?php
namespace App\Tests\Repository;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
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

    public function testAddUser()
    {
        $user = new User();
        $user->setID(100);
        $user->setUsername('TestUserRepositoryyololkakaka');
        $user->setEmail('test@yolo.yolokakaka');
        $user->setPassword('testkakaka');
        $user->setRoles(['ROLE_USER']);
        $this->entityManager->getRepository(User::class)->add($user, true);
        $this->assertNotNull($user);
        
        

    }

    public function testRemoveUser()
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => 10]);
        $this->entityManager->getRepository(User::class)->remove($user, true);
        $this->assertNull($user->getID());
    }
}
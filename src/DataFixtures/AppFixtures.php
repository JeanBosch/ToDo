<?php 

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
    
        for ($i = 0; $i < 4; $i++) {
            $task = new Task();
            $task->setTitle('Task ' . $i);
            $task->setContent('Content ' . $i);
            $task->setCreatedAt(new \DateTime());
            $task->setIsDone(false);
            $manager->persist($task);
            
        }

        $manager->flush();
    }
}
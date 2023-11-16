<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i < 4; $i++) {
            $user = new User();
            $user->setUsername("user$i");
            $user->setPassword('$2y$13$GAo.r5YolLSlARhalhckd.d8sFmQiwBOFb4LLPshzFX5yHdJ8YXfC');
            $manager->persist($user);

            $manager->flush();

            for ($taskI = 1; $taskI < 4; $taskI++) {
                $task = new Task();
                $task->setOwner($user);
                $task->setTitle("Task $taskI user " . $user->getId());
                $task->setDescription("Description");
                $task->setPriority(rand(1, 5));
                $manager->persist($task);

                for ($childTaskI = 1; $childTaskI < 3; $childTaskI++) {
                    $childTask = new Task();
                    $childTask->setOwner($user);
                    $childTask->setTitle("Subtask $childTaskI for task " . $task->getId() . " user " . $user->getId());
                    $childTask->setDescription("Description");
                    $childTask->setPriority(rand(1, 5));
                    $childTask->setParent($task);
                    $manager->persist($childTask);

                    $thirdLevelTask = new Task();
                    $thirdLevelTask->setOwner($user);
                    $thirdLevelTask->setTitle("3rd level task for task " . $childTask->getId() . " user " . $user->getId());
                    $thirdLevelTask->setDescription("Description");
                    $thirdLevelTask->setPriority(rand(1, 5));
                    $thirdLevelTask->setParent($childTask);
                    $manager->persist($thirdLevelTask);
                }
            }
        }
    }
}

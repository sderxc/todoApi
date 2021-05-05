<?php

namespace App\DataFixtures;

use App\Entity\TodoItem;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 0; $i < 50; $i++) {
            $todoItem = new TodoItem($faker->text(1000));
            $manager->persist($todoItem);
        }

        $manager->flush();
    }
}

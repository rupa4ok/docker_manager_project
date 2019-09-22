<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

abstract class BaseFixture extends Fixture
{
    private $baseManager;

    abstract protected function loadData(ObjectManager $baseManager): void;

    public function load(ObjectManager $manager): void
    {
        $this->baseManager = $manager;
        $this->loadData($manager);
    }
    protected function createMany(string $className, int $count, callable $factory)
    {
        for ($i = 0; $i < $count; $i++) {
            $entity = new $className();
            $factory($entity, $i);

            $this->baseManager->persist($entity);
            $this->addReference($className . '_' . $i, $entity);
        }
    }
}
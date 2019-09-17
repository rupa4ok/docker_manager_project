<?php

namespace App\DataFixtures;

use App\Model\User\Entity\Email;
use App\Model\User\Entity\Id;
use App\Model\User\Entity\Name;
use App\Model\User\Entity\User;
use App\Model\User\Service\PasswordHasher;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class UserFactoryFixtures extends BaseFixture
{
    private $hasher;
    private $manager;

    public function __construct(PasswordHasher $hasher, ObjectManager $manager)
    {
        $this->hasher = $hasher;
        $this->manager = $manager;
    }

    public function loadData(ObjectManager $manager): void
    {
        $this->createUsers(10, function($user, $count) {});
        $manager->flush();
    }

    protected function createUsers(int $count, callable $factory)
    {
        for ($i = 0; $i < $count; $i++) {
            $faker = Factory::create();
            $hasher = new PasswordHasher();

            $user = User::signUpByEmail(
                Id::next(),
                new \DateTimeImmutable(),
                new Name($faker->firstName(), $faker->lastName),
                new Email($faker->email),
                $hasher->hash($faker->numberBetween(6, 10)),
                'token'
            );

            $user->confirmSignUp();
            $factory($user, $i);
            $this->manager->persist($user);
            $this->addReference(User::class . '_' . $i, $user);
        }
    }
}
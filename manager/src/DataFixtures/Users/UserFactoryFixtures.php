<?php

namespace App\DataFixtures\Users;

use App\DataFixtures\BaseFixture;
use App\Model\User\Entity\User\ValueObject\Email;
use App\Model\User\Entity\User\ValueObject\Name;
use App\Model\User\Entity\User\User;
use App\Model\User\Entity\User\ValueObject\Id;
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
        $this->createUsers(
            15,
            function ($user, $count) {
            }
        );
        $manager->flush();
    }

    protected function createUsers(int $count, callable $factory)
    {
        for ($i = 0; $i < $count; $i++) {
            $faker = Factory::create('ru_RU');
            
            $user = User::signUpByEmail(
                Id::next(),
                new \DateTimeImmutable(),
                new Name($faker->firstName(), $faker->lastName),
                new Email($faker->email),
                $this->hasher->hash($faker->numberBetween(6, 10)),
                'token'
            );
            
            if (rand(1, 10) % 2 == 0) {
                $user->confirmSignUp();
            }
            
            $factory($user, $i);
            $this->manager->persist($user);
            $this->addReference(User::class . '_' . $i, $user);
        }
    }
}

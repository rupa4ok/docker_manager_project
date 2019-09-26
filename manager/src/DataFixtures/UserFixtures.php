<?php

namespace App\DataFixtures;

use App\Model\User\Entity\User\ValueObject\Email;
use App\Model\User\Entity\User\ValueObject\Name;
use App\Model\User\Entity\User\User;
use App\Model\User\Entity\User\ValueObject\Id;
use App\Model\User\Entity\User\ValueObject\Role;
use App\Model\User\Service\PasswordHasher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    private $hasher;

    public function __construct(PasswordHasher $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $hash = $this->hasher->hash('123456');

        $user = User::signUpByEmail(
            Id::next(),
            new \DateTimeImmutable(),
	        new Name('Test', 'Admin'),
            new Email('test@mail.ru'),
            $hash,
            'token'
        );

        $user->confirmSignUp();
        $user->changeRole(Role::admin());
        $manager->persist($user);
        $manager->flush();
    }
}

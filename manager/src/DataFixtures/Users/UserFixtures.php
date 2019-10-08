<?php

namespace App\DataFixtures\Users;

use App\Model\Company\Service\InnChecker\Inn;
use App\Model\User\Entity\User\User;
use App\Model\Company\Entity\Company;
use App\Model\User\Entity\User\ValueObject\Email;
use App\Model\User\Entity\User\ValueObject\Id;
use App\Model\Company\Entity\Id as CompanyId;
use App\Model\Company\Entity\Name as CompanyName;
use App\Model\User\Entity\User\ValueObject\Name;
use App\Model\User\Entity\User\ValueObject\Role;
use App\Model\User\Service\PasswordHasher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public const REFERENCE_ADMIN = 'user_user_admin';
    public const REFERENCE_USER = 'user_user_user';
    
    private $hasher;
    
    public function __construct(PasswordHasher $hasher)
    {
        $this->hasher = $hasher;
    }
    
    public function load(ObjectManager $manager): void
    {
        $hash = $this->hasher->hash('123456');
        
        $requested = $this->createSignUpRequestedByEmail(
            new Name('Test', 'NotConfirmed'),
            new Email('notconfirmed@app.test'),
            $hash
        );
        $manager->persist($requested);
        
        $confirmed = $this->createSignUpConfirmedByEmail(
            new Name('Test', 'Confirmed'),
            new Email('user@mail.ru'),
            $hash
        );
        $confirmed->addCompany(Company::create(
            CompanyId::next(),
            new \DateTimeImmutable(),
            new CompanyName('Test Full Name 2', 'Test Short Name 2'),
            new Inn(200246676)
        ));
        $manager->persist($confirmed);
        $this->setReference(self::REFERENCE_USER, $confirmed);
        
        $admin = $this->createAdminByEmail(
            new Name('Test', 'Admin'),
            new Email('test@mail.ru'),
            $hash
        );
        $admin->addCompany(Company::create(
            CompanyId::next(),
            new \DateTimeImmutable(),
            new CompanyName('Test Full Name', 'Test Short Name'),
            new Inn(190437290)
        ));
        $manager->persist($admin);
        $this->setReference(self::REFERENCE_ADMIN, $admin);
        
        $manager->flush();
    }
    
    private function createAdminByEmail(Name $name, Email $email, string $hash): User
    {
        $user = $this->createSignUpConfirmedByEmail($name, $email, $hash);
        $user->changeRole(Role::admin());
        return $user;
    }
    
    private function createSignUpConfirmedByEmail(Name $name, Email $email, string $hash): User
    {
        $user = $this->createSignUpRequestedByEmail($name, $email, $hash);
        $user->confirmSignUp();
        return $user;
    }
    
    private function createSignUpRequestedByEmail(Name $name, Email $email, string $hash): User
    {
        return User::signUpByEmail(
            Id::next(),
            new \DateTimeImmutable(),
            $name,
            $email,
            $hash,
            'token'
        );
    }
}

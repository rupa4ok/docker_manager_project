<?php

namespace App\DataFixtures\Company;

use App\Model\Company\Entity\Company;
use App\Model\Company\Entity\Id;
use App\Model\Company\Entity\Name;
use App\Model\Company\Service\InnChecker\Inn;
use App\Model\User\Service\PasswordHasher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CompanyFixtures extends Fixture
{
    private $hasher;

    public function __construct(PasswordHasher $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $company = Company::create(
            Id::next(),
            new \DateTimeImmutable(),
            new Name('Test', 'test short'),
            new Inn(100325912)
        );
        
        $manager->persist($company);
        $manager->flush();
    }
}

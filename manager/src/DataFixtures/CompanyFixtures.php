<?php

namespace App\DataFixtures;

use App\Model\Company\Entity\Company;
use App\Model\Company\Entity\Id;
use App\Model\Company\Entity\Name;
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
        $company = Company::signUp(
            Id::next(),
            new \DateTimeImmutable(),
	        new Name('Test', 'test short'),
	        '111111'
        );
        
        $manager->persist($company);
        $manager->flush();
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\Entity\User\SignUp;

use App\Model\Company\Entity\Company;
use App\Model\Company\Entity\Id;
use App\Model\Company\Entity\Name;
use App\Model\Company\Service\InnChecker\Inn;
use PHPUnit\Framework\TestCase;

class CreateTest extends TestCase
{
    public function testSuccess(): void
    {
        $company = Company::create(
            $id = Id::next(),
            $date = new \DateTimeImmutable(),
            $name = new Name('Test Company Full', 'Test Company Short'),
            $inn = new Inn('190437290')
        );

        self::assertNull($company->getAddress());
        
        self::assertEquals($company->getId(), $id);
        self::assertEquals($company->getDate(), $date);
        self::assertEquals($company->getName()->getFull(), $name->getFull());
        self::assertEquals($company->getName()->getShort(), $name->getShort());
    }
    
    public function testSuccessWithAdress(): void
    {
        $company = Company::create(
            $id = Id::next(),
            $date = new \DateTimeImmutable(),
            $name = new Name('Test Company Full', 'Test Company Short'),
            $inn = new Inn('190437290'),
        );
        
        $company->addAddress($address = 'Тест адрес');
        
        self::assertNotNull($company->getAddress());
        self::assertEquals($company->getAddress(), $address);
    }
}

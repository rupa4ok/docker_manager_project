<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Company\InnChecker;

use App\Model\Company\Service\InnChecker\Checker;
use App\Model\Company\Service\InnChecker\Inn;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testSuccess(): void
    {
        $inn = (new Checker())->check(new Inn($value = '190275968'));
        
        self::assertEquals($value, $inn->inn);
    }
    
    public function testEmpty(): void
    {
        $this->expectExceptionMessage('Expected a non-empty value');
        $inn = (new Checker())->check(new Inn($value = ''));
    }
}
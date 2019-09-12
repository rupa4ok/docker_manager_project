<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\Entity\User\SignUp;

use App\Model\User\Entity\Role;
use App\Tests\Builder\User\UserBuilder;
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new UserBuilder())->viaEmail()->build();
	
	    self::assertTrue($user->getRole()->isUser());
        
        $user->changeRole(Role::admin());
 
        self::assertTrue($user->getRole()->isAdmin());
        self::assertFalse($user->getRole()->isUser());
    }
    
    public function testAlready(): void
    {
	    $user = (new UserBuilder())->viaEmail()->build();
	    
	    $this->expectExceptionMessage('Роль уже установлена.');
	    
	    $user->changeRole(Role::user());
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\User\Entity\User\SignUp;

use PHPUnit\Framework\TestCase;
use App\Tests\Builder\User\UserBuilder;

class ConfirmTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = $this->buildSignedUpUser();
        $user->confirmSignUp();

        self::assertFalse($user->isWait());
        self::assertTrue($user->isActive());

        self::assertNull($user->getConfirmToken());
    }

    public function testAlready(): void
    {
        $user = $this->buildSignedUpUser();
        $user->confirmSignUp();
        $this->expectExceptionMessage('Пользователь уже подтвержден');
        $user->confirmSignUp();
    }

    private function buildSignedUpUser(): User
    {
        return (new UserBuilder())->viaEmail()->build();
    }
}

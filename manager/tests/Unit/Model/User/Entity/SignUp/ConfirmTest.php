<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\User\Entity\User\SignUp;

use App\Model\User\Entity\Email;
use App\Model\User\Entity\Id;
use App\Model\User\Entity\User;
use PHPUnit\Framework\TestCase;

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
        return new User(
            Id::next(),
            new \DateTimeImmutable(),
            new Email('test@test.ru'),
            'hash',
            $token = 'token'
        );
    }
}

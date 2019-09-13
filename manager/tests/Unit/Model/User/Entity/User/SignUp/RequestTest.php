<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\Entity\User\SignUp;

use App\Model\User\Entity\Email;
use App\Model\User\Entity\Id;
use App\Model\User\Entity\User;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = User::signUpByEmail(
            $id = Id::next(),
            $date = new \DateTimeImmutable(),
            $email = new Email('test@app.test'),
            $hash = 'hash',
            $token = 'token',
        );
 
        self::assertTrue($user->isWait());
        self::assertFalse($user->isActive());

        self::assertEquals($id, $user->getId());
        self::assertEquals($date, $user->getDate());
        self::assertEquals($email, $user->getEmail());
        self::assertEquals($hash, $user->getPasswordHash());
        self::assertEquals($token, $user->getConfirmToken());
        
        self::assertTrue($user->getRole()->isUser());
    }
}

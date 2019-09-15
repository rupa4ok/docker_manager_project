<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\Entity\User\NewEmail;

use App\Model\User\Entity\Email;
use App\Tests\Builder\User\UserBuilder;
use PHPUnit\Framework\TestCase;

class ConfirmTestTest extends TestCase
{
	public function testSuccess(): void
	{
		$user = (new UserBuilder())->viaEmail()->confirmed()->build();
		$user->requestEmailChanging(
			$email = new Email('new@mail.ru'),
			$token = 'token'
		);
		$user->confirmEmailChanging($token);
		
		self::assertEquals($email, $user->getEmail());
		self::assertNull($user->getNewEmail());
		self::assertNull($user->getNewEmailToken());
	}
	
	public function testNotRequested(): void
	{
		$user = (new UserBuilder())->viaEmail()->confirmed()->build();
		
		$this->expectExceptionMessage('Запрос смены email не найден.');
		$user->confirmEmailChanging('token');
	}
	
	public function testInccorect(): void
	{
		$user = (new UserBuilder())->viaEmail()->confirmed()->build();
		
		$user->requestEmailChanging(
			$email = new Email('new@mail.ru'),
			$token = 'token'
		);
		
		$this->expectExceptionMessage('Неправильный запрос смены email.');
		$user->confirmEmailChanging('wrong-token');
	}
}
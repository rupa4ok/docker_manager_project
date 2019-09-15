<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\Entity\User\NewEmail;

use App\Model\User\Entity\Email;
use App\Tests\Builder\User\UserBuilder;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
	public function testSuccess(): void
	{
		$user = (new UserBuilder())->viaEmail()->confirmed()->build();
		
		$user->requestEmailChanging(
			$email = new Email('new@mail.ru'),
			$token = 'token'
		);
		
		self::assertEquals($email, $user->getNewEmail());
		self::assertEquals($token, $user->getNewEmailToken());
	}
	
	public function testSame(): void
	{
		$user = (new UserBuilder())
			->viaEmail($email = new Email('new@mail.ru'))
			->confirmed()
			->build();
		
		$this->expectExceptionMessage('Email уже сменен.');
		
		$user->requestEmailChanging($email, 'token');
	}
	
	public function testNotConfirmed(): void
	{
		$user = (new UserBuilder())
			->viaEmail()
			->build();
		
		$this->expectExceptionMessage('Пользователь не активен.');
		$user->requestEmailChanging(new Email('new@mail.ru'), 'token');
	}
}
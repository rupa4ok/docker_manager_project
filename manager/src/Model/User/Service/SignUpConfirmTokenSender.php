<?php

declare(strict_types=1);

namespace App\Model\User\Service;

use App\Model\BaseSender;
use App\Model\User\Entity\Email;

class SignUpConfirmTokenSender extends BaseSender
{
	public function send(Email $email, string $token): void
	{
		$this->push($email, $token, 'Сайт CLEANTON.BY: Подтверждение регистрации', 'mail/user/signup.html.twig');
	}
}

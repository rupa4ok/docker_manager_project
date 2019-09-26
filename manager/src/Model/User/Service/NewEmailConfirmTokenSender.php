<?php

declare(strict_types=1);

namespace App\Model\User\Service;

use App\Model\BaseSender;
use App\Model\User\Entity\User\ValueObject\Email;


class NewEmailConfirmTokenSender extends BaseSender
{
	public function send(Email $email, string $token): void
	{
		$this->push($email, $token, 'Смена Email', 'mail/user/email.html.twig');
	}
}

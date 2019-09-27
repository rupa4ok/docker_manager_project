<?php

declare(strict_types=1);

namespace App\Model\User\Service;

use App\Model\BaseSender;
use App\Model\User\Entity\Email;
use App\Model\User\Entity\ResetToken;

class ResetTokenSender extends BaseSender
{
    public function send(Email $email, ResetToken $token): void
    {
        $this->push($email, $token, 'Сброс пароля', 'mail/user/reset.html.twig');
    }
}

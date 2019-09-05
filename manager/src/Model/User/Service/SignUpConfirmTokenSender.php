<?php

declare(strict_types=1);

namespace App\Model\User\Service;

use App\Model\User\Entity\Email;
use Twig\Environment;

class SignUpConfirmTokenSender
{
    private $mailer;
    private $twig;

    public function __construct(\Swift_Mailer $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function send(Email $email, string $token): void
    {
        $message = (new \Swift_Message('Сайт CLEANTON.BY: Подтверждение регистрации'))
            ->setTo($email->getValue())
            ->setBody($this->twig->render('mail/user/signup.html.twig', [
                'token' => $token
            ]), 'text/html');

        if (!$this->mailer->send($message)) {
            throw new \RuntimeException('Невозможно отправить сообщение.');
        }
    }
}

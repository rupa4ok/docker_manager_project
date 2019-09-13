<?php

declare(strict_types=1);

namespace App\Model\User\Service;

use App\Model\User\Entity\Email;
use App\Model\User\Entity\ResetToken;
use Twig\Environment;

class ResetTokenSender
{
    private $mailer;
    private $twig;

    public function __construct(\Swift_Mailer $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function send(Email $email, ResetToken $token): void
    {
        $message = (new \Swift_Message('Сброс пароля'))
            ->setTo($email->getValue())
            ->setBody($this->twig->render('mail/user/reset.html.twig', [
                'token' => $token->getToken()
            ]), 'text/html');

        if (!$this->mailer->send($message)) {
            throw new \RuntimeException('Невозможно отправить сообщение, обратитесь к администратору.');
        }
    }
}

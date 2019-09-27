<?php

declare(strict_types=1);

namespace App\Model;

use App\Model\User\Entity\User\ValueObject\Email;
use Twig\Environment;

class BaseSender
{
    private $mailer;
    private $twig;
    
    public function __construct(\Swift_Mailer $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }
    
    public function push(Email $email, $token, string $text, string $template): void
    {
        $message = (new \Swift_Message($text))
            ->setTo($email->getValue())
            ->setBody(
                $this->twig->render(
                    $template,
                    ['token' => $token]
                ),
                'text/html'
            );
        
        if (!$this->mailer->send($message)) {
            throw new \RuntimeException('Невозможно отправить сообщение, обратитесь к администратору.');
        }
    }
}

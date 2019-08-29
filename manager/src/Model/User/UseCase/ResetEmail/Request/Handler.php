<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\ResetEmail\Request;

use App\Model\Flusher;
use App\Model\User\Entity\Email;
use App\Model\User\Service\ResetTokenizer;
use App\Model\User\Service\ResetTokenSender;
use App\Model\User\Entity\UserRepository;

class Handler
{
    private $users;
    private $tokenizer;
    private $sender;
    private $flusher;
    public function __construct(
        UserRepository $users,
        ResetTokenizer $tokenizer,
        Flusher $flusher,
        ResetTokenSender $sender
    )
    {
        $this->users = $users;
        $this->sender = $tokenizer;
        $this->flusher = $flusher;
        $this->sender = $sender;
    }

    public function handle(Command $command): void
    {
        $user = $this->users->getByEmail(new Email($command->email));

        $user->requestPasswordReset(
            $this->tokenizer->generate(),
            new \DateTimeImmutable()
        );

        $this->flusher->flush();

        $this->sender->send($user->getEmail(), $user->getResetToken());
    }
}

<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\SignUp\Request;

use App\Model\Flusher;
use App\Model\User\Entity\Email;
use App\Model\User\Entity\Id;
use App\Model\User\Entity\Name;
use App\Model\User\Entity\User;
use App\Model\User\Entity\UserRepository;
use App\Model\User\Service\PasswordHasher;
use App\Model\User\Service\SignUpConfirmTokenizer;
use App\Model\User\Service\SignUpConfirmTokenSender;

class Handler
{
    private $users;
    private $hasher;
    private $tokenizer;
    private $sender;
    public function __construct(
        UserRepository $users,
        PasswordHasher $hasher,
        SignUpConfirmTokenizer $tokenizer,
        SignUpConfirmTokenSender $sender
    ) {
        $this->users = $users;
        $this->hasher = $hasher;
        $this->tokenizer = $tokenizer;
        $this->sender = $sender;
    }

    public function handle(Command $command, Flusher $flusher): void
    {
        $email = new Email($command->email);

        if ($this->users->hasByEmail($email)) {
            throw new \DomainException('Пользователь уже существует.');
        }

        $user = User::signUpByEmail(
            Id::next(),
            new \DateTimeImmutable(),
            new Name($command->firstName, $command->lastName),
            $email,
            $this->hasher->hash($command->password),
            $token = $this->tokenizer->generate()
        );

        $this->users->add($user);
        $this->sender->send($email, $token);
        $flusher->flush();
    }
}

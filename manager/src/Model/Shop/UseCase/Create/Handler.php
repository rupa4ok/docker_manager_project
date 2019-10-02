<?php

declare(strict_types=1);

namespace App\Model\Shop\UseCase\Create;

use App\Model\Flusher;
use App\Model\User\Entity\User\User;
use App\Model\User\Entity\User\UserRepository;
use App\Model\User\Entity\User\ValueObject\Email;
use App\Model\User\Entity\User\ValueObject\Id;
use App\Model\User\Entity\User\ValueObject\Name;
use App\Model\User\Service\PasswordGenerator;
use App\Model\User\Service\PasswordHasher;

class Handler
{
    private $users;
    private $hasher;
    private $flusher;
    private $generator;

    public function __construct(
        UserRepository $users,
        PasswordHasher $hasher,
        PasswordGenerator $generator,
        Flusher $flusher
    ) {
        $this->users = $users;
        $this->hasher = $hasher;
        $this->generator = $generator;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $email = new Email($command->email);

        if ($this->users->hasByEmail($email)) {
            throw new \DomainException('Пользователь уже существует.');
        }

        $user = User::create(
            Id::next(),
            new \DateTimeImmutable(),
            new Name($command->firstName, $command->lastName),
            $email,
            $this->generator->generate(),
        );

        $this->users->add($user);
        $this->flusher->flush();
    }
}

<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\SignUp\Confirm\ByToken;

use App\Model\Flusher;
use App\Model\User\Entity\Id;
use App\Model\User\Entity\UserRepository;

class Handler
{
    private $users;
    private $flusher;

    public function __construct(
        UserRepository $users,
        Flusher $flusher
    )
    {
        $this->users = $users;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $this->create(new Id($command->token));
    }

    public function create($param)
    {
        $user = $this->users->get($param);

        $user->confirmSignUp();

        $this->flusher->flush();
    }
}

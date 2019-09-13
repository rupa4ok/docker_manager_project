<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\SignUp\Confirm\Manual;

use App\Model\Flusher;
use App\Model\User\Entity\Id;
use App\Model\User\Entity\UserRepository;
use App\Model\User\UseCase\SignUp\Confirm\ByToken\Handler as Confirm;

class Handler
{
    private $users;
    private $flusher;
    private $confirm;

    public function __construct(
        UserRepository $users,
        Flusher $flusher,
        Confirm $confirm
    )
    {
        $this->users = $users;
        $this->flusher = $flusher;
        $this->confirm = $confirm;
    }

    public function handle(Command $command): void
    {
        $this->confirm->create(new Id($command->id));
    }
}

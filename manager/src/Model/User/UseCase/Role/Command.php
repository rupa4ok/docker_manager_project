<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Role;

class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $id;
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $role;

    public function __construct(string $id)
    {
        $this->id = $id;
    }
}

<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\ResetPassword\Request;

class Command
{
    /**
     * @var string
     */
    public $token;
    /**
     * @var string
     */
    public $password;
}
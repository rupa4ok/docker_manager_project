<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\ResetPassword;

use App\Model\User\Service\ResetTokenizer;

class ResetTokenizerFactory
{
    public function create(string $interval): ResetTokenizer
    {
        return new ResetTokenizer(new \DateInterval($interval));
    }
}
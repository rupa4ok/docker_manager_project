<?php
declare(strict_types=1);

namespace App\Model\User\Service;

class PasswordHasher
{
    public function hash(string $password): string
    {
        $hash = password_hash($password, PASSWORD_ARGON2I);
        if ($hash === false) {
            throw new \RuntimeException('Невозможно сгенерировать hash пароля');
        }
        return $hash;
    }
    public function validate(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
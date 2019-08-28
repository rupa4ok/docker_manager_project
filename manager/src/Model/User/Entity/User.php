<?php

declare(strict_types=1);

namespace App\Model\User\Entity;

use Doctrine\ORM\Mapping as ORM;

class User
{
    public const STATUS_WAIT = 'wait';
    public const STATUS_ACTIVE = 'active';

    /**
     * @ORM\Column(type="user_id")
     * @ORM\Id
     */
    private $id;

    /**
     * @var \DateTimeImmutable
     */
    private $date;

    /**
     * @var Email|null
     */
    private $email;

    /**
     * @var string|null
     */
    private $passwordHash;

    /**
     * @var string|null
     */
    private $confirmToken;

    /**
     * @var string
     * @ORM\Column(type="string", length=16)
     */
    private $status;

    /**
     * @param Id $id
     * @param \DateTimeImmutable $date
     * @param Email $email
     * @param string $hash
     * @param string $token
     */
    public function __construct(Id $id, \DateTimeImmutable $date, Email $email, string $hash, string $token)
    {
        $this->id = $id;
        $this->date = $date;
        $this->email = $email;
        $this->passwordHash = $hash;
        $this->confirmToken = $token;
        $this->status = self::STATUS_WAIT;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @return Email|null
     */
    public function getEmail(): ?Email
    {
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function getPasswordHash(): ?string
    {
        return $this->passwordHash;
    }

    /**
     * @return mixed
     */
    public function getConfirmToken()
    {
        return $this->confirmToken;
    }

    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function confirmSignUp(): void
    {
        if (!$this->isWait()) {
            throw new \DomainException(('Пользователь уже подтвержден'));
        }
        $this->status= self::STATUS_ACTIVE;
        $this->confirmToken = null;
    }

    public static function signUpByEmail(Id $id, \DateTimeImmutable $date, Email $email, string $hash, string $token): self
    {
        $user = new self($id, $date, $email, $hash, $token);
        $user->email = $email;
        $user->passwordHash = $hash;
        $user->confirmToken = $token;
        $user->status = self::STATUS_WAIT;
        return $user;
    }
}
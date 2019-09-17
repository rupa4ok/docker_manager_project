<?php

declare(strict_types=1);

namespace App\Model\User\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="user_users", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"email"}),
 *     @ORM\UniqueConstraint(columns={"reset_token_token"})
 * })
 */
class User
{
    public const STATUS_WAIT = 'wait';
    public const STATUS_ACTIVE = 'active';
	
	/**
	 * @ORM\Column(type="user_user_id")
	 * @ORM\Id
	 */
    private $id;
	/**
	 * @var \DateTimeImmutable
	 * @ORM\Column(type="datetime_immutable")
	 */
    private $date;
	/**
	 * @var Email|null
	 * @ORM\Column(type="user_user_email", nullable=true)
	 */
    private $email;
	/**
	 * @var Name
	 * @ORM\Embedded(class="Name")
	 */
    private $name;
	/**
	 * @var string|null
	 * @ORM\Column(type="string", name="confirm_token", nullable=true)
	 */
	private $confirmToken;
    /**
     * @var Email|null
     * @ORM\Column(type="user_user_email", name="new_email", nullable=true)
     */
	private $newEmail;
	/**
	 * @var string|null
	 * @ORM\Column(type="string", name="new_email_token", nullable=true)
	 */
	private $newEmailToken;
	/**
	 * @var string|null
	 * @ORM\Column(type="string", name="password_hash", nullable=true)
	 */
    private $passwordHash;
	/**
	 * @var string
	 * @ORM\Column(type="string", length=16)
	 */
    private $status;
    /**
     * @var Network[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="Network", mappedBy="user", orphanRemoval=true, cascade={"persist"})
     */
    private $networks;
	/**
	 * @var ResetToken|null
	 * @ORM\Embedded(class="ResetToken", columnPrefix="reset_token_")
	 */
    private $resetToken;
	/**
	 * @var Role
	 * @ORM\Column(type="user_user_role", length=16)
	 */
    private $role;
	
	/**
	 * @param Id $id
	 * @param \DateTimeImmutable $date
	 * @param Name $name
	 */
    public function __construct(Id $id, \DateTimeImmutable $date, Name $name)
    {
        $this->id = $id;
        $this->date = $date;
        $this->name = $name;
        $this->role = Role::user();
        $this->networks = new ArrayCollection();
    }

    public function confirmSignUp(): void
    {
        if (!$this->isWait()) {
            throw new \DomainException(('Пользователь уже подтвержден'));
        }
        $this->status= self::STATUS_ACTIVE;
        $this->confirmToken = null;
    }

    public static function signUpByEmail(
    	Id $id,
	    \DateTimeImmutable $date,
	    Name $name,
	    Email $email,
	    string $hash,
	    string $token): self
    {
        $user = new self($id, $date, $name);
        $user->email = $email;
        $user->passwordHash = $hash;
        $user->confirmToken = $token;
        $user->status = self::STATUS_WAIT;
        return $user;
    }

    public static function signUpByNetwork(
    	Id $id,
	    \DateTimeImmutable $date,
	    Name $name,
	    string $network,
	    string $identity): self
    {
        $user = new self($id, $date, $name);
        $user->attachNetwork($network, $identity);
        $user->status = self::STATUS_ACTIVE;
        return $user;
    }

    public function attachNetwork(string $network, string $identity): void
    {
        foreach ($this->networks as $existing) {
            if ($existing->isForNetwork($network)) {
                throw new \DomainException('Соцсеть уже подключена.');
            }
        }
        $this->networks->add(new Network($this, $network, $identity));
    }

    public function requestPasswordReset(ResetToken $token, \DateTimeImmutable $date): void
    {
        if (!$this->isActive()) {
            throw new \DomainException('Пользователь не активен.');
        }
        if (!$this->email) {
            throw new \DomainException('Email не найден.');
        }
        if ($this->resetToken && !$this->resetToken->isExpiredTo($date)) {
            throw new \DomainException('Запрос на сброс пароля уже отправлен.');
        }
        $this->resetToken = $token;
    }

    public function passwordReset(\DateTimeImmutable $date, string $hash): void
    {
        if (!$this->resetToken) {
            throw new \DomainException('Запрос на сброс пароля не отправлен.');
        }
        if ($this->resetToken->isExpiredTo($date)) {
            throw new \DomainException('Код подтверждения сброса пароля истек.');
        }
        $this->passwordHash = $hash;
        $this->resetToken = null;
    }
    
    public function confirmEmailChanging(string $token): void
    {
	    if (!$this->newEmailToken) {
		    throw new \DomainException('Запрос смены email не найден.');
	    }
	    if ($this->newEmailToken !== $token) {
		    throw new \DomainException('Неправильный запрос смены email.');
	    }
	    $this->email = $this->newEmail;
	    $this->newEmail = null;
	    $this->newEmailToken = null;
    }
	
	public function requestEmailChanging(Email $email, string $token): void
	{
		if (!$this->isActive()) {
			throw new \DomainException('Пользователь не активен.');
		}
		if ($this->email && $this->email->isEqual($email)) {
			throw new \DomainException('Email уже сменен.');
		}
		$this->newEmail = $email;
		$this->newEmailToken = $token;
	}
	
	public function changeRole(Role $role): void
	{
		if ($this->role->isEqual($role)) {
			throw new \DomainException('Роль уже установлена.');
		}
		$this->role = $role;
	}

    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
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
    public function getConfirmToken(): ?string
    {
        return $this->confirmToken;
    }

    /**
     * @return Network[]
     */
    public function getNetworks(): array
    {
        return $this->networks->toArray();
    }

    public function getResetToken(): ?ResetToken
    {
        return $this->resetToken;
    }
    
	public function getRole(): ?Role
	{
		return $this->role;
	}
	
	public function getNewEmail(): ?Email
	{
		return $this->newEmail;
	}
	
	public function getNewEmailToken(): ?string
	{
		return $this->newEmailToken;
	}

    /**
     * @return Name
     */
    public function getName(): Name
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }
	
	/**
	 * @ORM\PostLoad()
	 */
	public function checkEmbed(): void
	{
		if ($this->resetToken->isEmpty()) {
			$this->resetToken = null;
		}
	}
}
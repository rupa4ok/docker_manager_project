<?php

declare(strict_types=1);

namespace App\Tests\Builder\User;

use App\Model\User\Entity\Email;
use App\Model\User\Entity\Name;
use App\Model\User\Entity\Role;
use App\Model\User\Entity\User;
use App\Model\User\Entity\Id;

class UserBuilder
{
    private $id;
    private $date;
	private $name;

    private $email;
    private $hash;
    private $token;
    private $confirmed;

    private $network;
    private $identity;

    public function __construct()
    {
        $this->id = Id::next();
        $this->name = new Name('First', 'Last');
        $this->date = new \DateTimeImmutable();
    }

    public function viaEmail(Email $email = null, string $hash = null, string $token = null): self
    {
        $clone = clone $this;
        $clone->email = $email ?? new Email('mail@test.ru');
        $clone->hash = $hash ?? 'hash';
        $clone->token = $token ?? 'token';
        return $clone;
    }

    public function confirmed(): self
    {
        $clone = clone $this;
        $clone->confirmed = true;
        return $clone;
    }

    public function viaNetwork(string $network = null, string $identity = null): self
    {
        $clone = clone $this;
        $clone->network = $network ?? 'vk';
        $clone->identity = $identity ?? '0001';
        return $clone;
    }
	
	public function withId(Id $id): self
	{
		$clone = clone $this;
		$clone->id = $id;
		return $clone;
	}
	public function withName(Name $name): self
	{
		$clone = clone $this;
		$clone->name = $name;
		return $clone;
	}
	
	public function withRole(Role $role): self
	{
		$clone = clone $this;
		$clone->role = $role;
		return $clone;
	}

    public function build(): User
    {
        $user = null;

        if ($this->email) {
            $user = User::signUpByEmail(
                $this->id,
                $this->date,
                $this->name,
                $this->email,
                $this->hash,
                $this->token
            );

            if ($this->confirmed) {
                $user->confirmSignUp();
            }
        }

        if ($this->network) {
            $user = User::signUpByNetwork(
                $this->id,
                $this->date,
                $this->name,
                $this->network,
                $this->identity
            );
        }

        if (!$user) {
            throw new \BadMethodCallException('Specify via method.');
        }

        return $user;
    }
}

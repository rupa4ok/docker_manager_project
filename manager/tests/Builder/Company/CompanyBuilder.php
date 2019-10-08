<?php

declare(strict_types=1);

namespace App\Tests\Builder\User;

use App\Model\User\Entity\User\ValueObject\Id;
use App\Model\User\Entity\User\ValueObject\Name;

class CompanyBuilder
{
    private $id;
    private $date;
    private $name;
    private $inn;
    private $address;
    
    public function __construct()
    {
        $this->id = Id::next();
        $this->name = new Name('Test Company Full', 'Test Company Short');
        $this->date = new \DateTimeImmutable();
    }
    
    public function withAddress(string $address): self
    {
        $clone = clone $this;
        $clone->address = $address;
        return $clone;
    }
}

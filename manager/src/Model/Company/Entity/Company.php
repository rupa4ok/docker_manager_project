<?php

declare(strict_types=1);

namespace App\Model\Company\Entity;

use App\Model\Company\Service\InnChecker\Inn;
use App\Model\User\Entity\Network\Network;
use App\Model\User\Entity\User\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="user_company", uniqueConstraints={
 * @ORM\UniqueConstraint(columns={"name_full"}),
 * @ORM\UniqueConstraint(columns={"inn"})
 * })
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class Company
{
    /**
     * @ORM\Column(type="user_company_id")
     * @ORM\Id
     */
    private $id;
    /**
     * @var User[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Model\User\Entity\User\User",
     * mappedBy="company", cascade={"persist"}, fetch="EAGER")
     */
    private $users;
    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $date;
    
    /**
     * @var Name
     * @ORM\Embedded(class="Name")
     */
    private $name;
    /**
     * @var Inn
     * @ORM\Column(type="integer", nullable=false)
     */
    private $inn;
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $address;
    
    private function __construct(Id $id, \DateTimeImmutable $date, Name $name)
    {
        $this->id = $id;
        $this->date = $date;
        $this->name = $name;
    }
    
    public static function create(Id $id, \DateTimeImmutable $date, Name $name, Inn $inn): self
    {
        $company = new self($id, $date, $name);
        $company->inn = $inn;
        
        return $company;
    }
    
    public function addAddress(string $address): void
    {
        $this->address = $address;
    }
    
    /**
     * @return Id
     */
    public function getId(): Id
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
     * @return Name
     */
    public function getName(): Name
    {
        return $this->name;
    }
    
    /**
     * @return string
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }
    
    /**
     * @return User[]
     */
    public function getUsers(): array
    {
        return $this->users->toArray();
    }
    
    /**
     * @return Inn
     */
    public function getInn(): Inn
    {
        return $this->inn;
    }
}

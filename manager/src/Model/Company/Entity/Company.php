<?php

declare(strict_types=1);

namespace App\Model\Company\Entity;

use App\Model\Company\Service\InnChecker\Inn;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="user_company", uniqueConstraints={
 * @ORM\UniqueConstraint(columns={"name_full"}),
 * @ORM\UniqueConstraint(columns={"inn"})
 * })
 */
class Company
{
    /**
     * @ORM\Column(type="user_company_id")
     * @ORM\Id
     */
    private $id;
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
     * @var integer
     * @ORM\Column(type="integer", nullable=false)
     */
    private $inn;
    
    private function __construct(Id $id, \DateTimeImmutable $date, Name $name)
    {
        $this->id = $id;
        $this->date = $date;
        $this->name = $name;
    }
    
    public static function create(Id $id, \DateTimeImmutable $date, Name $name, Inn $inn): self
    {
        dump($date);
        $company = new self($id, $date, $name);
        $company->inn = $inn;
        
        return $company;
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
     * @return int
     */
    public function getInn(): int
    {
        return $this->inn;
    }
}

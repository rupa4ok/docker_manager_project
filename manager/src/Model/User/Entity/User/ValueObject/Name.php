<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User\ValueObject;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class Name
{
    /**
     * @var                       string
     * @ORM\Column(type="string", nullable=true)
     */
    private $first;
    /**
     * @var                       string
     * @ORM\Column(type="string", nullable=true)
     */
    private $last;
    
    public function __construct(?string $first, ?string $last)
    {
        $this->first = $first;
        $this->last = $last;
    }
    
    /**
     * @return string
     */
    public function getFirst(): string
    {
        return $this->first;
    }
    
    /**
     * @return string
     */
    public function getLast(): string
    {
        return $this->last;
    }
    
    public function getFull(): string
    {
        return $this->first . ' ' . $this->last;
    }
}
<?php

declare(strict_types=1);

namespace App\Model\User\Entity\UserInfo;

use App\Model\User\Entity\User\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="user_info", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"fax"}),
 * })
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class UserInfo
{
    /**
     * @ORM\Column(type="user_info_id")
     * @ORM\Id
     * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
     */
    private $id;
    /**
     * @var User
     * @ORM\OneToOne(targetEntity="App\Model\User\Entity\User\User", inversedBy="userInfo")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $user;
    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $position;
    /**
     * @var integer|null
     * @ORM\Column(type="integer", nullable=true)
     */
    private $fax;
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $deliveryAddress;
    
    private function __construct(Id $id)
    {
        $this->id = $id;
    }
    
    public static function create(Id $id, string $deliveryAddress): self
    {
        $user = new self($id);
        $user->deliveryAddress = $deliveryAddress;
        return $user;
    }
    
    /**
     * @return Id
     */
    public function getId(): Id
    {
        return $this->id;
    }
    
    /**
     * @return string|null
     */
    public function getPosition(): ?string
    {
        return $this->position;
    }
    
    /**
     * @return integer|null
     */
    public function getFax(): ?int
    {
        return $this->fax;
    }
    
    /**
     * @return string
     */
    public function getDeliveryAddress(): string
    {
        return $this->deliveryAddress;
    }
}

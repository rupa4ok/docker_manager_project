<?php

declare(strict_types=1);

namespace App\Model\Shop\Entity\Product;

use App\Model\Shop\Entity\Product\ValueObject\Id;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="shop_product_products")
 */
class Product
{
    /**
     * @ORM\Column(type="shop_product_products_id")
     * @ORM\Id
     */
    private $id;
    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $date;
    
    private function __construct(Id $id, \DateTimeImmutable $date)
    {
        $this->id = $id;
        $this->date = $date;
    }
    
    public static function create(Id $id, \DateTimeImmutable $date): self
    {
        return new self($id, $date);
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
}

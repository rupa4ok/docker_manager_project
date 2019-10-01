<?php

declare(strict_types=1);

namespace App\Model\Shop\Entity\Product;

use App\Model\Shop\Entity\Product\Product;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class ProductRepository
{
    private $em;
    /**
     * @var EntityRepository
     */
    private $repo;
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(Product::class);
    }

    public function add(Product $product): void
    {
        $this->em->persist($product);
    }
}

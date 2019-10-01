<?php

declare(strict_types=1);

namespace App\ReadModel\Shop\Product;

use App\Model\Work\Entity\Members\Member\Member;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

class ProductFetcher
{
    private $connection;
    private $paginator;
    private $repository;
    
    public function __construct(Connection $connection, EntityManagerInterface $em, PaginatorInterface $paginator)
    {
        $this->connection = $connection;
        $this->repository = $em->getRepository(Member::class);
        $this->paginator = $paginator;
    }
    
    public function insert(array $data): void
    {
        $this->connection->insert('shop_product_products', $data);
    }
    
    
}
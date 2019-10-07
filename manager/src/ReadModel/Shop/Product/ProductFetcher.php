<?php

declare(strict_types=1);

namespace App\ReadModel\Shop\Product;

use App\Model\Shop\Entity\Product\Product;
use App\ReadModel\Shop\Product\Filter\Filter;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class ProductFetcher
{
    private $connection;
    private $paginator;
    private $repository;
    
    public function __construct(Connection $connection, EntityManagerInterface $em, PaginatorInterface $paginator)
    {
        $this->connection = $connection;
        $this->repository = $em->getRepository(Product::class);
        $this->paginator = $paginator;
    }
    
    public function insert(array $data): void
    {
        try {
            $this->connection->insert('shop_product_products', $data);
        } catch (DBALException $e) {
            echo $e;
            return;
        }
    }
    
    public function all(Filter $filter, int $page, int $size, string $sort, string $direction): PaginationInterface
    {
        $qb = $this->connection->createQueryBuilder()
            ->select(
                'pr.id',
                'pr.date',
                'pr.name',
                'pr.article_post as post',
                'pr.article',
                'pr.brand',
                'pr.measures'
            )
            ->from('shop_product_products', 'pr');
        
        if ($filter->name) {
            $qb->andWhere($qb->expr()->like('LOWER(pr.name)', ':name'));
            $qb->setParameter(':name', '%' . mb_strtolower($filter->name) . '%');
        }
        
        $qb->orderBy($sort, $direction === 'desc' ? 'desc' : 'asc');
        
        return $this->paginator->paginate($qb, $page, $size);
    }
}

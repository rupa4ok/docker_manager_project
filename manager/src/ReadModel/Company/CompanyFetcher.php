<?php

declare(strict_types=1);

namespace App\ReadModel\Company;

use App\Model\Company\Entity\Company;
use App\ReadModel\NotFoundException;
use App\ReadModel\Company\Filter\Filter;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class CompanyFetcher
{
    private $connection;
    private $repository;
    private $paginator;
    
    /**
     * UserFetcher constructor.
     *
     * @param Connection             $connection
     * @param EntityManagerInterface $em
     * @param PaginatorInterface     $paginator
     */
    public function __construct(Connection $connection, EntityManagerInterface $em, PaginatorInterface $paginator)
    {
        $this->connection = $connection;
        $this->paginator = $paginator;
        $this->repository = $em->getRepository(Company::class);
    }
    
    public function get(string $id): Company
    {
        if (!$company = $this->repository->find($id)) {
            throw new NotFoundException('Компания не найдена');
        }
        return $company;
    }
    
    public function all(Filter $filter, int $page, int $size, string $sort, string $direction): PaginationInterface
    {
        $qb = $this->connection->createQueryBuilder()
            ->select(
                'id',
                'date',
                'name_full AS name',
                'inn',
            )
            ->from('user_company');
        if ($filter->name) {
            $qb->andWhere($qb->expr()->like('LOWER(CONCAT(name_full, \' \', name_short))', ':name'));
            $qb->setParameter(':name', '%' . mb_strtolower($filter->name) . '%');
        }
        if ($filter->inn) {
            $qb->andWhere($qb->expr()->like('LOWER(CONCAT(inn))', ':inn'));
            $qb->setParameter(':inn', '%' . mb_strtolower($filter->inn) . '%');
        }
        if (!\in_array($sort, ['date', 'name', 'inn'], true)) {
            throw new \UnexpectedValueException('Cannot sort by ' . $sort);
        }
        $qb->orderBy($sort, $direction === 'desc' ? 'desc' : 'asc');
        return $this->paginator->paginate($qb, $page, $size);
    }
    
    public function insert(array $data): void
    {
        try {
            $this->connection->insert('user_company', $data);
        } catch (DBALException $e) {
            return;
        }
    }
}

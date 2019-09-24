<?php

declare(strict_types=1);

namespace App\ReadModel\Company;

use App\Model\Company\Entity\Company;
use App\ReadModel\NotFoundException;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;


class CompanyFetcher
{
	private $connection;
	private $repository;
	private $paginator;
	
	/**
	 * UserFetcher constructor.
	 * @param Connection $connection
	 * @param EntityManagerInterface $em
	 * @param PaginatorInterface $paginator
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
}
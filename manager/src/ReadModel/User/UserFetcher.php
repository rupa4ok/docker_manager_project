<?php

declare(strict_types=1);

namespace App\ReadModel\User;

use App\Model\User\Entity\User\User;
use App\ReadModel\NotFoundException;
use App\ReadModel\User\Filter\Filter;
use App\ReadModel\User\View\AuthView;
use App\ReadModel\User\View\DetailView;
use App\ReadModel\User\View\NetworkView;
use App\ReadModel\User\View\ShortView;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;


class UserFetcher
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
        $this->repository = $em->getRepository(User::class);
    }
	
	public function get(string $id): User
	{
		if (!$user = $this->repository->find($id)) {
			throw new NotFoundException('Пользователь не найден');
		}
		return $user;
	}

    public function existsByResetToken(string $token): bool
    {
        return $this->connection->createQueryBuilder()
            ->select('COUNT (*)')
            ->from('user_users')
            ->where('reset_token_token = :token')
            ->setParameter(':token', $token)
            ->execute()->fetchColumn() > 0;
    }
    
    public function findForAuthByEmail(string $email): ?AuthView
    {
    	$stmt = $this->connection->createQueryBuilder()
		    ->select(
		    	'id',
			    'email',
			    'password_hash',
			    'role',
			    'status'
		    )
		    ->from('user_users')
		    ->where('email =:email')
		    ->setParameter(':email', $email)
		    ->execute();
    	$stmt->setFetchMode(FetchMode::CUSTOM_OBJECT, AuthView::class);
    	$result = $stmt->fetch();
    	
    	return $result ?: null;
    }

    public function findByEmail(string $email): ?ShortView
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'id',
                'email',
                'role',
                'status'
            )
            ->from('user_users')
            ->where('email = :email')
            ->setParameter(':email', $email)
            ->execute();
        $stmt->setFetchMode(FetchMode::CUSTOM_OBJECT, ShortView::class);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * @param string $id
     * @return DetailView|null
     */
    public function findDetail(string $id): ?DetailView
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'id',
                'date',
	            'TRIM(CONCAT(name_first, \' \', name_last)) AS name',
                'email',
                'role',
                'status'
            )
            ->from('user_users')
            ->where('id = :id')
            ->setParameter(':id', $id)
            ->execute();

        $stmt->setFetchMode(FetchMode::CUSTOM_OBJECT, DetailView::class);

        /** @var DetailView $view */
        $view = $stmt->fetch();

        $stmt = $this->connection->createQueryBuilder()
            ->select('network', 'identity')
            ->from('user_network_networks')
            ->where('user_id = :id')
            ->setParameter(':id', $id)
            ->execute();

        $stmt->setFetchMode(FetchMode::CUSTOM_OBJECT, NetworkView::class);

        $view->network = $stmt->fetchAll();

        return $view;
    }

	public function findBySignUpConfirmToken(string $token): ?ShortView
	{
		$stmt = $this->connection->createQueryBuilder()
			->select(
				'id',
				'email',
				'role',
				'status'
			)
			->from('user_users')
			->where('confirm_token = :token')
			->setParameter(':token', $token)
			->execute();
		$stmt->setFetchMode(FetchMode::CUSTOM_OBJECT, ShortView::class);
		$result = $stmt->fetch();
		return $result ?: null;
	}
	
	/**
	 * @param Filter $filter
	 * @param int $page
	 * @param int $size
	 * @param string $sort
	 * @param string $direction
	 * @return PaginationInterface
	 */
	public function all(Filter $filter, int $page, int $size, string $sort, string $direction): PaginationInterface
	{
		$qb = $this->connection->createQueryBuilder()
			->select(
				'id',
				'date',
				'TRIM(CONCAT(name_first, \' \', name_last)) AS name',
				'email',
				'role',
				'status'
			)
			->from('user_users');
		if ($filter->name) {
			$qb->andWhere($qb->expr()->like('LOWER(CONCAT(name_first, \' \', name_last))', ':name'));
			$qb->setParameter(':name', '%' . mb_strtolower($filter->name) . '%');
		}
		if ($filter->email) {
			$qb->andWhere($qb->expr()->like('LOWER(email)', ':email'));
			$qb->setParameter(':email', '%' . mb_strtolower($filter->email) . '%');
		}
		if ($filter->status) {
			$qb->andWhere('status = :status');
			$qb->setParameter(':status', $filter->status);
		}
		if ($filter->role) {
			$qb->andWhere('role = :role');
			$qb->setParameter(':role', $filter->role);
		}
		if (!\in_array($sort, ['date', 'name', 'email', 'role', 'status'], true)) {
			throw new \UnexpectedValueException('Cannot sort by ' . $sort);
		}
		$qb->orderBy($sort, $direction === 'desc' ? 'desc' : 'asc');
		return $this->paginator->paginate($qb, $page, $size);
	}
}
<?php

declare(strict_types=1);

namespace App\ReadModel\User;

use App\Model\User\Entity\User;
use App\ReadModel\NotFoundException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;
use Doctrine\ORM\EntityManagerInterface;

class UserFetcher
{
    private $connection;
    private $repository;

    public function __construct(Connection $connection, EntityManagerInterface $em)
    {
        $this->connection = $connection;
        $this->repository = $em->getRepository(User::class);
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
}
<?php

declare(strict_types=1);

namespace App\Model\Company\Entity;

use App\Model\Company\Service\InnChecker\Inn;
use App\Model\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class CompanyRepository
{
    private $em;
    /**
     * @var EntityRepository
     */
    private $repo;
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(Company::class);
    }
    
    public function findAll()
    {
        return $this->repo->findAll();
    }
    
    public function hasByInn(Inn $inn): bool
    {
        return $this->repo->createQueryBuilder('c')
                ->select('COUNT(c.id)')
                ->andWhere('c.inn = :inn')
                ->setParameter(':inn', $inn)
                ->getQuery()->getSingleScalarResult() > 0;
    }
    
    public function get(string $id): Company
    {
        /*** @var Company $company */
        if (!$company = $this->repo->find($id)) {
            throw new EntityNotFoundException('Компания не найдена.');
        }
        return $company;
    }
    
    public function add(Company $company): void
    {
        $this->em->persist($company);
    }
}
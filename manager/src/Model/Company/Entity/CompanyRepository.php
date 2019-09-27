<?php

declare(strict_types=1);

namespace App\Model\Company\Entity;

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
}
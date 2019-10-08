<?php

declare(strict_types=1);

namespace App\Model\Company\UseCase\Create;

use App\Model\Company\Entity\Company;
use App\Model\Company\Entity\CompanyRepository;
use App\Model\Company\Entity\Id;
use App\Model\Company\Entity\Name;
use App\Model\Company\Service\InnChecker\Checker;
use App\Model\Company\Service\InnChecker\Inn;
use App\Model\Flusher;
use DateTimeImmutable;

class Handler
{
    private $company;
    private $innChecker;
    private $flusher;

    public function __construct(
        CompanyRepository $company,
        Flusher $flusher,
        Checker $innChecker
    ) {
        $this->company = $company;
        $this->innChecker = $innChecker;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $inn = new Inn($command->inn);
        
        if ($this->company->hasByInn($inn)) {
            throw new \DomainException('Компания уже зарегистрирована.');
        }
        
        if (!$company = $this->innChecker->check($inn)) {
            throw new \DomainException('Такой компании не существует.');
        }

        $company = Company::create(
            Id::next(),
            DateTimeImmutable::createFromFormat('d.m.Y', $company->reg),
            new Name(
                $company->full,
                $company->short
            ),
            $inn
        );

        $this->company->add($company);
        $this->flusher->flush();
    }
}

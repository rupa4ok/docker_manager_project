<?php

declare(strict_types=1);

namespace App\Command\Company;

use App\Model\User\UseCase\SignUp\Confirm;
use App\Model\User\UseCase\SignUp;
use App\ReadModel\Company\CompanyFetcher;
use App\ReadModel\User\UserFetcher;
use App\Services\CouchDb\Connector as CompanyList;
use App\Model\Company\UseCase\Create\ImportCommand as Company;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ImportCommand extends Command
{
    private $companyFetcher;
    private $userFetcher;
    private $fetcher;
    
    public function __construct(CompanyFetcher $companyFetcher, CompanyList $fetcher, UserFetcher $userFetcher)
    {
        parent::__construct();
        $this->fetcher = $fetcher;
        $this->companyFetcher = $companyFetcher;
        $this->userFetcher = $userFetcher;
    }
    
    protected function configure(): void
    {
        $this
            ->setName('company:import')
            ->setDescription('Import product list');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $output->writeln('<info>Импорт компаний и пользователей из couchdb</info>');
        
        $companyList = $this->fetcher->getProductList('ut_users');
        
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $metadataAwareNameConverter = new MetadataAwareNameConverter($classMetadataFactory);
        
        $serializer = new Serializer(
            [new ObjectNormalizer($classMetadataFactory, $metadataAwareNameConverter)],
            ['json' => new JsonEncoder()]
        );
        
        $progressBar = new ProgressBar($output, count($companyList['rows']));
        $progressBar->setFormat('debug');
        $progressBar->start();
        
        foreach ($companyList['rows'] as $item) {
            $progressBar->advance();
            
            $data = $serializer->serialize($item['doc']['data'], 'json');
            $companyList = $serializer->deserialize($data, Company::class, 'json');
            
            $this->create($companyList);
        }
        
        $progressBar->finish();
        
        $output->writeln('<info>Done!</info>');
    }
    
    private function create(Company $company)
    {
        if ($company->status == 'U') {
            $date = '2019-10-01 09:13:37';
    
            $products = [
                'id' => $company->id,
                'date' => $date,
                'inn' => $company->inn,
                'name_full' => $company->name,
                'name_short' => $company->short
            ];
            
            $this->addUsers($company->users, $company->id);
    
            $this->companyFetcher->insert($products);
        }
    }
    
    private function addUsers(?array $user, string $companyId)
    {
        if ($user) {
            foreach ($user as $item) {
                $user = [
                    'id' => $item['guid'],
                    'date' => '2019-10-01 09:13:37',
                    'email' => $item['email'],
                    'name_first' => $item['name'],
                    'name_last' => '',
                    'status' => 'active',
                    'role' => 'ROLE_USER',
                    'company_id' => $companyId
                ];
        
                $this->userFetcher->insert($user);
            }
        }
    }
}

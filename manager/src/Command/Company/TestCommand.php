<?php

declare(strict_types=1);

namespace App\Command\Company;

use App\Model\Company\UseCase\Create\ObjectOuter;
use App\Model\Company\UseCase\Create\ObjectUsers;
use App\Model\User\UseCase\SignUp\Confirm;
use App\Model\User\UseCase\SignUp;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use App\ReadModel\Company\CompanyFetcher;
use App\Services\CouchDb\Connector as ProductList;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Console\Command\Command;

class TestCommand extends Command
{
    private $companyFetcher;
    private $fetcher;
    
    public function __construct(CompanyFetcher $companyFetcher, ProductList $fetcher)
    {
        parent::__construct();
        $this->fetcher = $fetcher;
        $this->companyFetcher = $companyFetcher;
    }
    
    protected function configure(): void
    {
        $this
            ->setName('company:test')
            ->setDescription('Import product list');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $output->writeln('<info>Импорт компаний и пользователей из couchdb</info>');
        
        $productList = $this->fetcher->getProductList('ut_users');
    
        
        $normalizer = new ObjectNormalizer(null, null, null, new ReflectionExtractor());
        $serializer = new Serializer([new DateTimeNormalizer(), $normalizer]);
        
        $progressBar = new ProgressBar($output, count($productList['rows']));
        $progressBar->setFormat('debug');
        $progressBar->start();
        
        foreach ($productList['rows'] as $item) {
            $progressBar->advance();
            $data = $item['doc']['data'];
            
            $productList = $serializer->denormalize(
                $data,
                ObjectOuter::class
            );
            
            $productList = $serializer->denormalize(
                $productList->getContacts()[0],
                ObjectUsers::class
            );
    
            print_r($productList);
        }
        
        $progressBar->finish();
        
        $output->writeln('<info>Done!</info>');
    }
}

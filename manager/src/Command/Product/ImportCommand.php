<?php

declare(strict_types=1);

namespace App\Command\Product;

use App\Model\Shop\Entity\Product\ValueObject\Id;
use App\Model\User\UseCase\SignUp\Confirm;
use App\Model\User\UseCase\SignUp;
use App\ReadModel\Shop\Product\ProductFetcher;
use App\Services\CouchDb\ProductFetcher as ProductList;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends Command
{
    private $product;
    private $fetcher;
    
    public function __construct(ProductFetcher $product, ProductList $fetcher)
    {
        parent::__construct();
        $this->fetcher = $fetcher;
        $this->product = $product;
    }
    
    protected function configure(): void
    {
        $this
            ->setName('product:import')
            ->setDescription('Import product list');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $output->writeln('<info>Импорт guid товаров из couchdb</info>');
    
        $productList = $this->fetcher->getProductList();
    
        var_dump($productList);
        
        $progressBar = new ProgressBar($output, count($productList));
        $progressBar->setFormat('debug');
        $progressBar->start();
    
        foreach ($productList as $item) {
            $this->create($item);
            $progressBar->advance();
        }
        
        $progressBar->finish();
        
        $output->writeln('<info>Done!</info>');
    }
    
    private function create($id)
    {
        $date = '2019-10-01 09:13:37';

        $products = [
            'id' => $id,
            'date' => $date
        ];

        $this->product->insert($products);
    }
}

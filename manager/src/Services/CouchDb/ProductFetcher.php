<?php

declare(strict_types=1);

namespace App\Services\CouchDb;

use Doctrine\CouchDB\CouchDBClient;
use Symfony\Component\HttpClient\HttpClient;

class ProductFetcher
{
    public function getProductList()
    {
        $client = CouchDBClient::create(array(
            'dbname' => 'ut_products',
            'ip' => '192.168.17.157',
            'port' => 41301,
            'user' => 'root',
            'password' => 'super_secret_password',
            'timeout' => 10
        ));
        
        return $client->allDocs()->body;
    }
}

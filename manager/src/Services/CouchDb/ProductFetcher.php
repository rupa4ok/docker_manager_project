<?php

declare(strict_types=1);

namespace App\Services\CouchDb;

use Doctrine\CouchDB\CouchDBClient;

class ProductFetcher
{
    public function getProductList($database)
    {
        $client = CouchDBClient::create(array(
            'dbname' => $database,
            'ip' => '192.168.17.157',
            'port' => 41301,
            'user' => 'root',
            'password' => 'super_secret_password',
            'timeout' => 10
        ));
        
        return $client->allDocs(1000)->body;
    }
}

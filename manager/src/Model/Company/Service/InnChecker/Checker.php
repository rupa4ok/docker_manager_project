<?php

declare(strict_types=1);

namespace App\Model\Company\Service\InnChecker;

class Checker
{
    public function check(Inn $inn): ?CheckerDto
    {
        $url = 'http://www.portal.nalog.gov.by/grp/getData?' . http_build_query([
                'unp' => $inn->getValue(),
                'type' => 'json'
            ]);
        
        try {
            $response = file_get_contents($url);
        } catch (\Exception $e) {
            return null;
        }
        
        $inn = json_decode($response, true);
        
        return new CheckerDto($inn['ROW']);
    }
}

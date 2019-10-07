<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class JsonExtension extends AbstractExtension
{
    public function getName()
    {
        return 'twig.json_decode';
    }
    
    public function getFilters()
    {
        return array(
            new TwigFilter('json_decode', [$this, 'jsonDecode'], ['is_safe' => ['json']]),
        );
    }
    
    public function jsonDecode($string)
    {
        return json_decode($string);
    }
}
<?php

namespace App\Model\Company\UseCase\Create;

use App\Model\Company\UseCase\Create\ObjectInner;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ObjectOuter
{
    private $contacts;
    private $name;
    
    public function getContacts()
    {
        return $this->contacts;
    }
    
    public function setContacts($contacts)
    {
        $this->contacts = $contacts;
    }
    
    public function setName($name)
    {
        $this->name = $name;
    }
    
    public function getName()
    {
        return $this->name;
    }
}

<?php

declare(strict_types=1);

namespace App\Model\Company\UseCase\Create;

use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class ImportCommand
{
    /**
     * @SerializedName("guid")
     */
    private $id;
    private $inn;
    private $name;
    private $persons;
    
    public function setPersons(OuterCommand $persons)
    {
        $this->persons = $persons;
    }
    
    public function getPersons()
    {
        return $this->persons;
    }
    
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @return mixed
     */
    public function getInn()
    {
        return $this->inn;
    }
    
    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
}

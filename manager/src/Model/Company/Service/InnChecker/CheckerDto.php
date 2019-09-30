<?php

declare(strict_types=1);

namespace App\Model\Company\Service\InnChecker;

class CheckerDto
{
    public $inn;
    public $full;
    public $short;
    public $address;
    public $reg;
    public $status;
    
    public function __construct(array $inn)
    {
        $this->inn = $inn['VUNP'];
        $this->full = $inn['VNAIMP'];
        $this->short = $inn['VNAIMK'];
        $this->address = $inn['VPADRES'];
        $this->reg = $inn['DREG'];
        $this->status = $inn['CKODSOST'];
    }
}

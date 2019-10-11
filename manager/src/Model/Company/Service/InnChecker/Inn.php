<?php

declare(strict_types=1);

namespace App\Model\Company\Service\InnChecker;

use Webmozart\Assert\Assert;

class Inn
{
    private $value;
    
    public function __construct(string $inn)
    {
        Assert::notEmpty($inn);
        $this->value = $inn;
    }
    
    public function getValue(): ?string
    {
        return $this->value;
    }
    
    public function __toString(): ?string
    {
        return $this->getValue();
    }
}

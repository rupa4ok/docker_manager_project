<?php

declare(strict_types=1);

namespace App\Model\Company\Entity\Type;

use App\Model\Company\Service\InnChecker\Inn;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class InnType extends StringType
{
    public const NAME = 'company_user_inn';
    
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof Inn ? $value->getValue() : $value;
    }
    
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new Inn($value) : null;
    }
    
    public function getName(): string
    {
        return self::NAME;
    }
}

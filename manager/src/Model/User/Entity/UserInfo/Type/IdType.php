<?php

declare(strict_types=1);

namespace App\Model\User\Entity\UserInfo\Type;

use App\Model\User\Entity\UserInfo\Id;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class IdType extends StringType
{
    public const NAME = 'user_info_id';
    
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof Id ? $value->getValue() : $value;
    }
    
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new Id($value) : null;
    }
    
    public function getName(): string
    {
        return self::NAME;
    }
}

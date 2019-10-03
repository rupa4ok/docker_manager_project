<?php

declare(strict_types=1);

namespace App\Model\Company\UseCase\Create;

use Symfony\Component\Validator\Constraints as Assert;

class OuterCommand
{
    public $name;
    public $guid;
}

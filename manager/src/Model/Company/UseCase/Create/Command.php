<?php

declare(strict_types=1);

namespace App\Model\Company\UseCase\Create;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @var string
     */
    public $fullName;
    /**
     * @var string
     */
    public $shortName;
    /**
     * @var string
     * @Assert\NotNull()
     */
    public $inn;
}

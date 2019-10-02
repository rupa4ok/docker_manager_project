<?php

declare(strict_types=1);

namespace App\Model\Shop\UseCase\Create;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @var string
     */
    public $id;
    public $date;
    public $name;
}

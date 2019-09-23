<?php

declare(strict_types=1);

namespace App\ReadModel\User\View;

use App\Model\User\Entity\Network;

class DetailView
{
    public $id;
    public $date;
    public $name;
    public $email;
    public $role;
    public $status;
    /**
     * @var Network
     */
    public $networks;
}
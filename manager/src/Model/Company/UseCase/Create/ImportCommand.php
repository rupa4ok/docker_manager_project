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
    public $id;
    public $inn;
    /**
     * @SerializedName("full_name")
     */
    public $name;
    /**
     * @SerializedName("name")
     */
    public $short;
    /**
     * @SerializedName("legal_status")
     */
    public $status;
    public $users;
}

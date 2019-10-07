<?php

declare(strict_types=1);

namespace App\Model\Shop\UseCase\Create;

use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class ImportCommand
{
    /**
     * @SerializedName("guid")
     */
    public $id;
    public $date;
    public $name;
    /**
     * @SerializedName("articul_post")
     */
    public $articlePost;
    public $measures;
    public $brand;
}

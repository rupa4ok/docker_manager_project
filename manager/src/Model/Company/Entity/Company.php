<?php

declare(strict_types=1);

namespace App\Model\Company\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="user_company", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"name"}),
 *     @ORM\UniqueConstraint(columns={"inn"})
 * })
 */
class Company
{
    /**
     * @ORM\Column(type="user_company_id")
     * @ORM\Id
     */
    private $id;
    /**
     * @var Name
     * @ORM\Embedded(class="Name")
     */
    private $name;
    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=false)
     */
    private $inn;
}

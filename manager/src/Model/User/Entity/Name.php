<?php

declare(strict_types=1);

namespace App\Model\User\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class Name
{
	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	private $first;
	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	private $last;
	
	public function __construct(string $first, string $last)
	{
		$this->first = $first;
		$this->last = $last;
	}
	
	/**
	 * @return string
	 */
	public function getFirst(): string
	{
		return $this->first;
	}
	
	/**
	 * @return string
	 */
	public function getLast(): string
	{
		return $this->last;
	}
}
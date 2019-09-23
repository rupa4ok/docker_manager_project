<?php

declare(strict_types=1);

namespace App\Model\Company\Entity;

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
	private $full;
	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	private $short;
	
	public function __construct(string $full, string $short)
	{
		$this->full = $full;
		$this->short = $short;
	}
	
	/**
	 * @return string
	 */
	public function getFull(): string
	{
		return $this->full;
	}
	
	/**
	 * @return string
	 */
	public function getShort(): string
	{
		return $this->short;
	}
}
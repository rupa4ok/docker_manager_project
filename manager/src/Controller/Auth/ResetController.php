<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Model\User\UseCase\ResetPassword;
use Symfony\Flex\Response;

class ResetController extends AbstractController
{
	private $logger;
	
	public function __construct(LoggerInterface $logger)
	{
		$this->logger = $logger;
	}
	
	public function request(Request $request, ResetPassword\Request\Handler $handler): Response
	{
		$command = new ResetPassword\Request\Command();
		
		$form = $this->createForm(ResetPassword\Request\Form::class, $command);
		$form->handleRequest($request);
	}
}
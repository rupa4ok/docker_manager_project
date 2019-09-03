<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Model\User\UseCase\SignUp;
use App\Security\LoginFormAuthenticator;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class SignUpController extends AbstractController
{
	private $logger;
	
	public function __construct(LoggerInterface $logger)
	{
		$this->logger = $logger;
	}
	
	/**
	 * @Route("/signup", name="auth.signup")
	 * @param Request $request
	 * @param SignUp\Request\Handler $handler
	 * @return Response
	 */
	public function request(Request $request, SignUp\Request\Handler $handler): Response
	{
		$command = new SignUp\Request\Command();
		
		$form = $this->createForm(SignUp\Request\Form::class, $command);
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {
			try {
				$handler->handle($command);
				$this->addFlash('succes', 'Проверьте вашу почту');
				return $this->redirectToRoute('home');
			} catch (\DomainException $e) {
				$this->logger->error($e->getMessage(), ['exception' => $e]);
				$this->addFlash('error', $e->getMessage());
			}
		}
		
		return $this->render('app/auth/signup.html.twig', [
			'form' => $form->createView()
		]);
	}
}

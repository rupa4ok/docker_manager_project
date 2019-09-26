<?php

declare(strict_types=1);

namespace App\Controller\Profile;

use App\Model\User\UseCase\NewEmail;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile/password", name="profile")
 */
class PasswordController extends AbstractController
{
	private $logger;
	
	public function __construct(LoggerInterface $logger)
	{
		$this->logger = $logger;
	}
	
	/**
	 * @Route("", name=".password")
	 * @param Request $request
	 * @param NewEmail\Request\Handler $handler
	 * @return Response
	 */
	public function request(Request $request, NewEmail\Request\Handler $handler): Response
	{
		$command = new NewEmail\Request\Command($this->getUser()->getId());
		
		$form = $this->createForm(NewEmail\Request\Form::class, $command);
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {
			try {
				$handler->handle($command);
				$this->addFlash('success', 'Проверьте вашу почту');
				return $this->redirectToRoute('profile');
			} catch (\DomainException $e) {
				$this->logger->error($e->getMessage(), ['exception' => $e]);
				$this->addFlash('error', $e->getMessage());
			}
		}
		
		return $this->render('app/profile/email.html.twig', [
			'form' => $form->createView()
		]);
	}

    /**
     * @Route("/{token}", name=".email.confirm")
     * @param string $token
     * @param NewEmail\Confirm\Handler $handler
     * @return Response
     */
	public function confirm(string $token, NewEmail\Confirm\Handler $handler): Response
	{
		$command = new NewEmail\Confirm\Command($this->getUser()->getId(), $token);
		
		try {
			$handler->handle($command);
			$this->addFlash('success', 'Email успешно подтвержден');
			return $this->redirectToRoute('profile');
		} catch (\DomainException $e) {
			$this->logger->error($e->getMessage(), ['exception' => $e]);
			$this->addFlash('error', $e->getMessage());
			return $this->redirectToRoute('profile');
		}
	}
}

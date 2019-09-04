<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\ReadModel\User\UserFetcher;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Model\User\UseCase\ResetPassword;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResetController extends AbstractController
{
	private $logger;
	
	public function __construct(LoggerInterface $logger)
	{
		$this->logger = $logger;
	}

    /**
     * @Route("/reset", name="auth.reset")
     * @param Request $request
     * @param ResetPassword\Request\Handler $handler
     * @return Response
     */
    public function request(Request $request, ResetPassword\Request\Handler $handler): Response
    {
        $command = new ResetPassword\Request\Command();

        $form = $this->createForm(ResetPassword\Request\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                $this->addFlash('success', 'Check your email.');
                return $this->redirectToRoute('home');
            } catch (\DomainException $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('app/auth/reset/request.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/reset/{token}", name="auth.reset.reset")
     * @param string $token
     * @param Request $request
     * @param ResetPassword\Reset\Handler $handler
     * @param UserFetcher $users
     * @return Response
     */
    public function reset(string $token, Request $request, ResetPassword\Reset\Handler $handler, UserFetcher $users): Response
    {
        if (!$users->existsByResetToken($token)) {
            $this->addFlash('error', 'Incorrect or already confirmed token.');
            return $this->redirectToRoute('home');
        }

        $command = new ResetPassword\Reset\Command($token);

        $form = $this->createForm(ResetPassword\Reset\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                $this->addFlash('success', 'Password is successfully changed.');
                return $this->redirectToRoute('home');
            } catch (\DomainException $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('app/auth/reset/reset.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
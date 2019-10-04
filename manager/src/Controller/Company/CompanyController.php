<?php

declare(strict_types=1);

namespace App\Controller\Company;

use App\Model\User\Entity\User\User;
use App\Model\User\UseCase\Create;
use App\Model\User\UseCase\Role;
use App\Model\User\UseCase\SignUp\Confirm;
use App\Model\User\UseCase\Edit;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user_company/", name="user_company")
 */
class CompanyController extends AbstractController
{
    private $logger;
    
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    
    /**
     * @Route("", name="")
     * @param Request $request
     * @param Create\Handler $handler
     * @return Response
     */
    public function show(Request $request, Create\Handler $handler): Response
    {
        $command = new Create\Command();
    
        $form = $this->createForm(Create\Form::class, $command);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                $this->addFlash('success', 'Компания успешно создана');
                return $this->redirectToRoute('users');
            } catch (\DomainException $e) {
                $this->logger->warning($e->getMessage(), ['exception' => $e]);
                $this->addFlash('error', $e->getMessage());
            }
        }
        
        $company = '00a7e66c-cbef-4efa-b6cd-85253d2357e0';
        
        return $this->render(
            'app/users/company/show.html.twig',
            [
                'company' => $company,
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("create", name=".create")
     * @param           Request        $request
     * @param           Create\Handler $handler
     * @return          Response
     */
    public function create(Request $request, Create\Handler $handler): Response
    {
        $command = new Create\Command();
    
        $form = $this->createForm(Create\Form::class, $command);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                $this->addFlash('success', 'Пользователь успешно создан');
                return $this->redirectToRoute('users');
            } catch (\DomainException $e) {
                $this->logger->warning($e->getMessage(), ['exception' => $e]);
                $this->addFlash('error', $e->getMessage());
            }
        }
    
        return $this->render(
            'app/users/create.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name=".edit")
     * @param               User         $user
     * @param               Request      $request
     * @param               Edit\Handler $handler
     * @return              Response
     */
    public function edit(User $user, Request $request, Edit\Handler $handler): Response
    {
        $command = Edit\Command::fromUser($user);

        $form = $this->createForm(Edit\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                $this->addFlash('success', 'Пользователь успешно отредактирован');
                return $this->redirectToRoute('users.show', ['id' => $user->getId()]);
            } catch (\DomainException $e) {
                $this->logger->warning($e->getMessage(), ['exception' => $e]);
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render(
            'app/users/edit.html.twig',
            [
            'user' => $user,
            'form' => $form->createView()
            ]
        );
    }
    
    /**
     * @Route("/{id}/role", name=".role")
     * @param               User         $user
     * @param               Request      $request
     * @param               Role\Handler $handler
     * @return              Response
     */
    public function role(User $user, Request $request, Role\Handler $handler): Response
    {
        if ($user->getId()->getValue() === $this->getUser()->getId()) {
            $this->addFlash('error', 'Невозможно изменить свою роль.');
            return $this->redirectToRoute('users.show', ['id' => $user->getId()]);
        }

        $command = Role\Command::fromUser($user);
        
        $form = $this->createForm(Role\Form::class, $command);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                $this->addFlash('success', 'Пользователь успешно отредактирован');
                return $this->redirectToRoute('users.show', ['id' => $user->getId()]);
            } catch (\DomainException $e) {
                $this->logger->warning($e->getMessage(), ['exception' => $e]);
                $this->addFlash('error', $e->getMessage());
            }
        }
        
        return $this->render(
            'app/users/role.html.twig',
            [
            'user' => $user,
            'form' => $form->createView()
            ]
        );
    }


    /**
     * @Route("/{id}/confirm", name=".confirm", methods={"POST"})
     * @param                  User                   $user
     * @param                  Request                $request
     * @param                  Confirm\Manual\Handler $handler
     * @return                 Response
     */
    public function confirm(User $user, Request $request, Confirm\Manual\Handler $handler): Response
    {
        if (!$this->isCsrfTokenValid('confirm', $request->request->get('token'))) {
            return $this->redirectToRoute('users.show', ['id' => $user->getId()]);
        }
        $command = new Confirm\Manual\Command($user->getId()->getValue());
        try {
            $handler->handle($command);
        } catch (\DomainException $e) {
            $this->logger->warning($e->getMessage(), ['exception' => $e]);
            $this->addFlash('error', $e->getMessage());
        }
        return $this->redirectToRoute('users.show', ['id' => $user->getId()]);
    }
}

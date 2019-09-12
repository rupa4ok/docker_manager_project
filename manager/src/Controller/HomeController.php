<?php

namespace App\Controller;

use App\Model\User\Entity\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    /**
     * @Route("/", name="home")
     * @param UserRepository $users
     * @return Response
     */
    public function index(UserRepository $users)
    {
        $user = $users->findAll();

        return $this->render('app/home.html.twig', [
            'controller_name' => 'HomeController',
            'users' => $user
        ]);
    }
}

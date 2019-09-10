<?php

namespace App\Controller;

use App\Model\User\Entity\UserRepository;
use App\ReadModel\User\UserFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param UserRepository $users
     * @return \Symfony\Component\HttpFoundation\Response
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

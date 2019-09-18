<?php

namespace App\Controller;

use App\ReadModel\User\UserFetcher;
use Elastica\Query;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
	
	/**
	 * @Route("/", name="home")
	 * @param UserFetcher $users
	 * @return Response
	 */
    public function index(UserFetcher $users)
    {

        $users ='';

        phpinfo();

        return $this->render('app/home.html.twig', [
            'users' => $users
        ]);
    }
}

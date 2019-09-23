<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @return Response
     */
    public function index()
    {
        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', 'http://www.portal.nalog.gov.by/grp/getData?unp=100582333&charset=UTF-8&type=json');

        $inn = $response->getContent();

        dump($inn);

        $users = '';
        
        return $this->render('app/home.html.twig', [
            'users' => $users
        ]);
    }
}

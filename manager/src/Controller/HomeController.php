<?php

namespace App\Controller;

use App\Model\Company\Entity\CompanyRepository;
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
    public function index(CompanyRepository $company)
    {
        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', 'http://www.portal.nalog.gov.by/grp/getData?unp=390325329&charset=UTF-8&type=json');

        $inn = $response->getContent();
        $inn = json_decode($inn);

        dump($inn);

        $company = $company->findAll();
        
        return $this->render('app/home.html.twig', [
            'companies' => $company,
            'inn' => $inn
        ]);
    }
}

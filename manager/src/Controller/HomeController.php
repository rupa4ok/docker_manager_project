<?php

namespace App\Controller;

use App\Model\Company\Service\InnChecker\Checker;
use App\Model\Company\Service\InnChecker\Inn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param Checker $checker
     * @return     Response
     */
    public function index(Checker $checker)
    {
        $inn = $checker->check(new Inn(190275968));
        
        dump($inn);
        
        return $this->render(
            'app/home.html.twig',
            [
            'inn' => $inn
            ]
        );
    }
}

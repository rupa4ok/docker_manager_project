<?php

namespace App\Controller\Shop;

use App\ReadModel\Shop\Product\Filter\Filter;
use App\ReadModel\Shop\Product\ProductFetcher;
use App\Services\Redis\RedisHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private const PER_PAGE = 10;
    private $redisHelper;
    
    public function __construct(RedisHelper $redisHelper)
    {
        $this->redisHelper = $redisHelper;
    }
    
    /**
     * @Route("/product", name="products")
     * @param Request $request
     * @param ProductFetcher $products
     * @return     Response
     */
    public function index(Request $request, ProductFetcher $products)
    {
        $filter = new Filter();
    
        $pagination = $products->all(
            $filter,
            $request->query->getInt('page', 1),
            self::PER_PAGE,
            $request->query->get('sort', 'date'),
            $request->query->get('direction', 'desc')
        );

        return $this->render(
            'app/products/index.html.twig',
            [
            'pagination' => $pagination
            ]
        );
    }
    
    /**
     * @param Request $request
     *
     * @Route("/set")
     *
     * @return Response
     */
    public function setAction(Request $request)
    {
        $key = 'test';
        $value = '123';
        
        $result = null;
        
        try {
            if ($key && $value) {
                $this->redisHelper->set($key, $value);
                $result = ['key' => $key, 'value' => $value];
            }
        } catch (\DomainException $e) {
            $result = $e->getMessage();
        }
        
        return new Response(json_encode($result));
    }
    
    /**
     * @param Request $request
     *
     * @Route("/get")
     *
     * @return Response
     */
    public function getAction(Request $request)
    {
        $key = $request->query->get('key');
        
        $result = null;
        
        try {
            if ($key) {
                $result = ['key' => $key, 'value' => $this->redisHelper->get($key)];
            }
        } catch (\DomainException $e) {
            $result = $e->getMessage();
        }
        
        return new Response(json_encode($result));
    }
}

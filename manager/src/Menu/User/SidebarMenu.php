<?php

declare(strict_types=1);

namespace App\Menu\User;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class SidebarMenu
{
    private $factory;
    private $auth;
    
    public function __construct(FactoryInterface $factory, AuthorizationCheckerInterface $auth)
    {
        $this->factory = $factory;
        $this->auth = $auth;
    }
    
    public function build(): ItemInterface
    {
        $menu = $this->factory->createItem('root')
            ->setChildrenAttributes(['class' => 'nav']);
        
        $menu->addChild('Рабочий стол', ['route' => 'home'])
            ->setExtra('icon', 'nav-icon icon-speedometer')
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link');
        
        $menu->addChild('Моя компания', ['route' => 'user_company'])
            ->setExtra('icon', 'nav-icon icon-people')
            ->setExtra('routes', [
                ['route' => 'users'],
                ['pattern' => '/^users\..+/']
            ])
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link');
    
        $menu->addChild('Филиалы / отделы', ['route' => 'user_company'])
            ->setExtra('icon', 'nav-icon icon-people')
            ->setExtra('routes', [
                ['route' => 'users'],
                ['pattern' => '/^users\..+/']
            ])
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link');
        
        $menu->addChild('Профиль', ['route' => 'profile'])
            ->setExtra('icon', 'nav-icon icon-user')
            ->setExtra('routes', [
                ['route' => 'profile'],
                ['pattern' => '/^profile\..+/']
            ])
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link');
        
        return $menu;
    }
}

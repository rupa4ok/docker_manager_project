<?php

declare(strict_types=1);

namespace App\Menu\Admin;

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
        
        $menu->addChild('Главная', ['route' => 'home'])
            ->setExtra('icon', 'nav-icon icon-speedometer')
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link');
        
        $menu->addChild('Work')->setAttribute('class', 'nav-title');
        
        if ($this->auth->isGranted('ROLE_WORK_MANAGE_MEMBERS')) {
            $menu->addChild('Members', ['route' => 'work.members'])
                ->setExtra('routes', [
                    ['route' => 'work.members'],
                    ['pattern' => '/^work.members\..+/']
                ])
                ->setExtra('icon', 'nav-icon icon-people')
                ->setAttribute('class', 'nav-item')
                ->setLinkAttribute('class', 'nav-link');
        }
        
        $menu->addChild('Control')->setAttribute('class', 'nav-title');
        
        if ($this->auth->isGranted('ROLE_MANAGE_USERS')) {
            $menu->addChild('Пользователи', ['route' => 'users'])
                ->setExtra('icon', 'nav-icon icon-people')
                ->setExtra('routes', [
                    ['route' => 'users'],
                    ['pattern' => '/^users\..+/']
                ])
                ->setAttribute('class', 'nav-item')
                ->setLinkAttribute('class', 'nav-link');
        }
    
        if ($this->auth->isGranted('ROLE_WORK_MANAGE_MEMBERS')) {
            $menu->addChild('Компании', ['route' => 'company'])
                ->setExtra('routes', [
                    ['route' => 'company'],
                    ['pattern' => '/^company\..+/']
                ])
                ->setExtra('icon', 'nav-icon icon-people')
                ->setAttribute('class', 'nav-item')
                ->setLinkAttribute('class', 'nav-link');
        }
        
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

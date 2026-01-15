<?php

namespace Streamflix;

class Router
{
    public function dispatch()
    {
        $route = $_GET['route'] ?? 'home';
        
        switch ($route) {
            case 'home':
            case '':
                require __DIR__ . '/../pages/home.php';
                break;
            case 'film':
                require __DIR__ . '/../pages/film.php';
                break;
            case 'login':
                require __DIR__ . '/../pages/login.php';
                break;
            case 'register':
                require __DIR__ . '/../pages/register.php';
                break;
            case 'cart':
                require __DIR__ . '/../pages/cart.php';
                break;
            case 'subscription':
                require __DIR__ . '/../pages/subscription.php';
                break;
            case 'catalogue':
                require __DIR__ . '/../pages/home.php';
                break;
            case 'api':
                require __DIR__ . '/../api/handler.php';
                break;
            default:
                require __DIR__ . '/../pages/home.php';
        }
    }
}


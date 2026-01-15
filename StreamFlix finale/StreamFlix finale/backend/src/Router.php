<?php

namespace Streamflix;

class Router
{
    private $basePath;
    
    public function __construct()
    {
        $this->basePath = dirname(__DIR__, 2);
    }
    
    public function dispatch()
    {
        $route = $_GET['route'] ?? 'home';
        
        switch ($route) {
            case 'home':
            case '':
                require $this->basePath . '/frontend/pages/home.php';
                break;
            case 'film':
                require $this->basePath . '/frontend/pages/film.php';
                break;
            case 'login':
                require $this->basePath . '/frontend/pages/login.php';
                break;
            case 'register':
                require $this->basePath . '/frontend/pages/register.php';
                break;
            case 'cart':
                require $this->basePath . '/frontend/pages/cart.php';
                break;
            case 'subscription':
                require $this->basePath . '/frontend/pages/subscription.php';
                break;
            case 'catalogue':
                require $this->basePath . '/frontend/pages/catalogue.php';
                break;
            case 'search':
                require $this->basePath . '/frontend/pages/search.php';
                break;
            case 'api':
                require $this->basePath . '/backend/api/handler.php';
                break;
            case 'subscription-confirm':
                require $this->basePath . '/frontend/pages/subscription-confirm.php';
                break;
            case 'subscription-success':
                require $this->basePath . '/frontend/pages/subscription-success.php';
                break;
            case 'checkout':
                require $this->basePath . '/frontend/pages/checkout.php';
                break;
            case 'purchase-success':
                require $this->basePath . '/frontend/pages/purchase-success.php';
                break;
            case 'purchases':
                require $this->basePath . '/frontend/pages/purchases.php';
                break;
            case 'account':
                require $this->basePath . '/frontend/pages/account.php';
                break;
            case 'watch':
                require $this->basePath . '/frontend/pages/watch.php';
                break;
            default:
                require $this->basePath . '/frontend/pages/home.php';
        }
    }
}


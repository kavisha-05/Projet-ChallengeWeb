<?php
session_start();

require_once __DIR__ . '/backend/config.php';
require_once __DIR__ . '/backend/vendor/autoload.php';

use Streamflix\Router;

$router = new Router();
$router->dispatch();

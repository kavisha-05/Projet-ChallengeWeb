<?php
session_start();

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'addToCart':
        $id = $_POST['id'] ?? $_GET['id'] ?? null;
        $title = $_POST['title'] ?? $_GET['title'] ?? '';
        $poster = $_POST['poster'] ?? $_GET['poster'] ?? '';
        
        if ($id) {
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            
            $found = false;
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['id'] == $id) {
                    $item['quantity'] = ($item['quantity'] ?? 1) + 1;
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                $_SESSION['cart'][] = [
                    'id' => $id,
                    'title' => $title,
                    'poster' => $poster,
                    'quantity' => 1
                ];
            }
            
            echo json_encode(['success' => true, 'count' => count($_SESSION['cart'])]);
        }
        break;
        
    case 'removeFromCart':
        $id = $_GET['id'] ?? $_POST['id'] ?? null;
        if ($id && isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array_filter($_SESSION['cart'], function($item) use ($id) {
                return $item['id'] != $id;
            });
            $_SESSION['cart'] = array_values($_SESSION['cart']);
            echo json_encode(['success' => true]);
        }
        break;
        
    case 'updateCart':
        $id = $_GET['id'] ?? $_POST['id'] ?? null;
        $change = intval($_GET['change'] ?? $_POST['change'] ?? 0);
        if ($id && isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['id'] == $id) {
                    $item['quantity'] = max(1, ($item['quantity'] ?? 1) + $change);
                    break;
                }
            }
            echo json_encode(['success' => true]);
        }
        break;
        
    case 'login':
        // Simple login simulation
        $_SESSION['user'] = [
            'username' => $_POST['username'] ?? 'user'
        ];
        require_once __DIR__ . '/../includes/functions.php';
        header('Location: ' . route_url());
        exit;
        
    case 'register':
        // Simple registration simulation
        if ($_POST['password'] === $_POST['password_confirm']) {
            $_SESSION['user'] = [
                'username' => $_POST['username'] ?? 'user',
                'email' => $_POST['email'] ?? ''
            ];
            require_once __DIR__ . '/../includes/functions.php';
        header('Location: ' . route_url());
            exit;
        } else {
            require_once __DIR__ . '/../includes/functions.php';
            header('Location: ' . route_url('register', ['error' => 'password']));
            exit;
        }
        break;
        
    default:
        echo json_encode(['error' => 'Invalid action']);
}


<?php
// Session déjà démarrée dans index.php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../../frontend/includes/functions.php';

use Streamflix\Database;
use Streamflix\UserModel;
use Streamflix\CartModel;
use Streamflix\SubscriptionModel;
use Streamflix\PurchaseModel;

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'addToCart':
        $id = $_POST['id'] ?? $_GET['id'] ?? null;
        $title = $_POST['title'] ?? $_GET['title'] ?? '';
        $poster = $_POST['poster'] ?? $_GET['poster'] ?? '';
        
        if ($id) {
            // Initialiser le panier en session si nécessaire
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            
            // Vérifier si le film est déjà dans le panier
            $found = false;
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['id'] == $id) {
                    $item['quantity'] = ($item['quantity'] ?? 1) + 1;
                    $found = true;
                    break;
                }
            }
            
            // Ajouter le film s'il n'est pas déjà présent
            if (!$found) {
                $_SESSION['cart'][] = [
                    'id' => $id,
                    'title' => $title,
                    'poster' => $poster,
                    'quantity' => 1
                ];
            }
            
            $cartCount = count($_SESSION['cart']);
            echo json_encode(['success' => true, 'count' => $cartCount]);
        } else {
            echo json_encode(['success' => false, 'error' => 'ID manquant']);
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
        $userModel = new UserModel();
        $email = $_POST['email'] ?? $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if ($email && $password) {
            $user = $userModel->verifyPassword($email, $password);
            if ($user) {
                $_SESSION['user'] = [
                    'id' => $user['id_utilisateur'],
                    'nom' => $user['nom'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                    'id_panier' => $user['id_panier']
                ];
                header('Location: ' . route_url());
                exit;
            } else {
                header('Location: ' . route_url('login', ['error' => 'invalid']));
                exit;
            }
        } else {
            header('Location: ' . route_url('login', ['error' => 'missing']));
            exit;
        }
        break;
        
    case 'register':
        $userModel = new UserModel();
        $nom = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';
        
        if ($password === $passwordConfirm && $nom && $email && $password) {
            // Vérifier si l'email existe déjà
            $existing = $userModel->getUserByEmail($email);
            if ($existing) {
                header('Location: ' . route_url('register', ['error' => 'email_exists']));
                exit;
            }
            
            try {
                $userId = $userModel->createUser($nom, $email, $password);
                $user = $userModel->getUserById($userId);
                
                $_SESSION['user'] = [
                    'id' => $user['id_utilisateur'],
                    'nom' => $user['nom'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                    'id_panier' => $user['id_panier']
                ];
                
                header('Location: ' . route_url());
                exit;
            } catch (Exception $e) {
                header('Location: ' . route_url('register', ['error' => 'database']));
                exit;
            }
        } else {
            header('Location: ' . route_url('register', ['error' => 'password']));
            exit;
        }
        break;
        
    case 'subscribe':
        $plan = $_POST['plan'] ?? '';
        $price = $_POST['price'] ?? '';
        
        if ($plan && $price && isset($_SESSION['user'])) {
            $subscriptionModel = new SubscriptionModel();
            $dateDebut = date('Y-m-d');
            $dateFin = date('Y-m-d', strtotime('+1 month'));
            
            try {
                $subscriptionModel->createSubscription(
                    $_SESSION['user']['id'],
                    $plan,
                    $dateDebut,
                    $dateFin
                );
                
                $_SESSION['subscription_plan'] = $plan;
                header('Location: ' . route_url('subscription-success'));
                exit;
            } catch (Exception $e) {
                header('Location: ' . route_url('subscription', ['error' => 'database']));
                exit;
            }
        } else {
            // Si pas connecté, utiliser la session
            if ($plan && $price) {
                $_SESSION['subscription'] = [
                    'plan' => $plan,
                    'price' => $price,
                    'active' => true,
                    'start_date' => date('Y-m-d'),
                    'end_date' => date('Y-m-d', strtotime('+1 month'))
                ];
                $_SESSION['subscription_plan'] = $plan;
                header('Location: ' . route_url('subscription-success'));
                exit;
            } else {
                header('Location: ' . route_url('subscription'));
                exit;
            }
        }
        break;
        
    case 'processPurchase':
        if (!isset($_SESSION['user'])) {
            header('Location: ' . route_url('login'));
            exit;
        }
        
        $items = json_decode($_POST['items'] ?? '[]', true);
        $total = floatval($_POST['total'] ?? 0);
        
        if (empty($items)) {
            header('Location: ' . route_url('cart', ['error' => 'empty']));
            exit;
        }
        
        try {
            $purchaseModel = new PurchaseModel();
            $purchaseIds = $purchaseModel->createPurchase($_SESSION['user']['id'], $items);
            
            // Vider le panier
            if (isset($_SESSION['user']['id_panier'])) {
                $cartModel = new CartModel();
                $cartModel->clearCart($_SESSION['user']['id_panier']);
            } else {
                $_SESSION['cart'] = [];
            }
            
            // Stocker les infos de la commande
            $_SESSION['last_purchase_id'] = $purchaseIds[0] ?? null;
            $_SESSION['last_purchase_date'] = date('Y-m-d H:i:s');
            
            header('Location: ' . route_url('purchase-success'));
            exit;
        } catch (Exception $e) {
            header('Location: ' . route_url('checkout', ['error' => 'database']));
            exit;
        }
        break;
        
    case 'logout':
        // Déconnexion
        $_SESSION = [];
        session_destroy();
        header('Location: ' . route_url());
        exit;
        break;
        
    default:
        echo json_encode(['error' => 'Invalid action']);
}


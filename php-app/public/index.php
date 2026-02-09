<?php
// php-app/public/index.php

// Autoloader (manual for now, can perform `composer dump-autoload` if we add composer later)
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../src/';
    $len = strlen($prefix);

    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Start Session
session_start();

// Simple Router
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Basic Routing Logic
switch ($requestUri) {
    case '/':
        require __DIR__ . '/../templates/home.php';
        break;
    
    // Auth Views
    case '/login':
        if ($method === 'POST') {
            (new \App\Controllers\AuthController())->login();
        } else {
            require __DIR__ . '/../templates/auth/login.php';
        }
        break;
    
    case '/register':
        if ($method === 'POST') {
            (new \App\Controllers\AuthController())->register();
        } else {
            require __DIR__ . '/../templates/auth/register.php';
        }
        break;
        
    case '/logout':
        (new \App\Controllers\AuthController())->logout();
        break;

    // Item Routes
    case '/items/create':
        if ($method === 'POST') {
            (new \App\Controllers\ItemController())->create();
        } else {
            require __DIR__ . '/../templates/items/create.php';
        }
        break;
        
    case '/items/view':
        $id = $_GET['id'] ?? null;
        if ($id) {
            (new \App\Controllers\ItemController())->show($id);
        } else {
            header('Location: /');
        }
        break;

    case '/items/edit':
        $id = $_GET['id'] ?? null;
        if ($id) {
            (new \App\Controllers\ItemController())->edit($id);
        } else {
            header('Location: /');
        }
        break;

    case '/items/update':
        if ($method === 'POST') {
            (new \App\Controllers\ItemController())->update();
        } else {
            header('Location: /');
        }
        break;

    case '/items/delete':
        $id = $_GET['id'] ?? $_POST['id'] ?? null;
        if ($id) {
            (new \App\Controllers\ItemController())->delete($id);
        } else {
            header('Location: /');
        }
        break;

    case '/messages':
        (new \App\Controllers\MessageController())->index();
        break;

    case '/messages/send':
        if ($method === 'POST') {
            (new \App\Controllers\MessageController())->send();
        } else {
            header('Location: /');
        }
        break;

    case '/search':
        (new \App\Controllers\SearchController())->index();
        break;

    case '/setup-db.php':
    case '/setup-db':
        require __DIR__ . '/setup-db.php';
        break;

    default:
        http_response_code(404);
        require __DIR__ . '/../templates/404.php';
        break;
}

<?php
// ============================================================
//  CarLoc – Point d'entrée unique (Front Controller)
// ============================================================

session_start();

// Autoload simple
spl_autoload_register(function($class) {
    $paths = [
        __DIR__ . '/../app/controllers/' . $class . '.php',
        __DIR__ . '/../app/models/'      . $class . '.php',
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) { require_once $path; return; }
    }
});

require_once __DIR__ . '/../config/database.php';

// ── Routeur ──────────────────────────────────────────────────
$url    = trim($_GET['url'] ?? '', '/');
$parts  = explode('/', $url);

$controllerName = !empty($parts[0]) ? ucfirst($parts[0]) . 'Controller' : 'HomeController';
$action         = $parts[1] ?? 'index';
$param          = $parts[2] ?? null;

$controllerFile = __DIR__ . '/../app/controllers/' . $controllerName . '.php';

// Contrôleur introuvable → 404
if (!file_exists($controllerFile)) {
    require_once __DIR__ . '/../app/controllers/HomeController.php';
    (new HomeController())->notFound();
    exit;
}

require_once $controllerFile;
$controller = new $controllerName();

// Méthode introuvable → 404
if (!method_exists($controller, $action)) {
    require_once __DIR__ . '/../app/controllers/HomeController.php';
    (new HomeController())->notFound();
    exit;
}

$controller->$action($param);

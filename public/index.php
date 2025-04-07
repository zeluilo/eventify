<?php

require '../functions/loadTemplate.php';
require '../database.php';
require '../clasess/DatabaseTable.php';
require '../controllers/UsersController.php';
require '../controllers/EventController.php';
require '../controllers/CategoryController.php';

$userTable = new DatabaseTable($pdo, 'users', 'userId');
$categoryTable = new DatabaseTable($pdo, 'category', 'categoryId');
$eventTable = new DatabaseTable($pdo, 'events', 'eventId');
$viewEventDetails = new DatabaseTable($pdo, 'view_event_details', 'eventId');

$controllers = [];
$controllers['users'] = new \Controllers\UsersController($userTable, $eventTable);
$controllers['category'] = new \Controllers\CategoryController($categoryTable, $eventTable);
$controllers['events'] = new \Controllers\EventController($categoryTable, $eventTable, $userTable, $viewEventDetails);

$route = ltrim(explode('?', $_SERVER['REQUEST_URI'])[0], '/');

if ($route == '') {
    $page = $controllers['users']->home();
} else {
    $parts = explode('/', $route);
    $controllerName = $parts[0] ?? null;
    $functionName = $parts[1] ?? null;

    // Check if controller exists
    if (!isset($controllers[$controllerName])) {
        http_response_code(404);
        $page = [
            'template' => '404.php',
            'title' => 'Page Not Found',
            'variables' => []
        ];
    } else {
        $controller = $controllers[$controllerName];

        // Ensure $functionName is a valid string before calling the method
        if ($functionName && is_string($functionName) && method_exists($controller, $functionName)) {
            $page = $controller->$functionName();
        } else {
            http_response_code(404);
            $page = [
                'template' => '404.php',
                'title' => 'Page Not Found',
                'variables' => []
            ];
        }
    }
}

// Render page
$output = loadTemplate('../pages/' . $page['template'], $page['variables']);
$title = $page['title'];
require '../pages/layout.php';

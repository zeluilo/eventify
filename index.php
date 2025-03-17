<?php

require '../functions/loadTemplate.php';
require '../database.php';
require '../classes/DatabaseTable.php';
require '../Controllers/AdminController.php';

$userTable = new DatabaseTable($pdo, 'users', 'userId');
$categoryTable = new DatabaseTable($pdo, 'category', 'categoryId');
$eventTable = new DatabaseTable($pdo, 'events', 'eventId');

$controllers = [];
$controllers['users'] = new \Controllers\UsersController($userTable);
$controllers['category'] = new \Controllers\CategoryController($categoryTable);
$controllers['events'] = new \Controllers\EventController($eventTable);

$route = ltrim(explode('?', $_SERVER['REQUEST_URI'])[0], '/');

    if ($route == '') {
        $page = $controllers['admin']->home();
    } else {
        list($controllerName, $functionName) = explode('/', $route);
        $controller = $controllers[$controllerName];
        $page = $controller->$functionName();
    }

    $output = loadTemplate('../templates/' . $page['template'], $page['variables']);
    $title = $page['title'];
    require '../templates/layout.html.php';


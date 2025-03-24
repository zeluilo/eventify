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
$controllers['users'] = new \Controllers\UsersController($userTable);
$controllers['category'] = new \Controllers\CategoryController($categoryTable, $eventTable);
$controllers['events'] = new \Controllers\EventController($categoryTable, $eventTable, $userTable, $viewEventDetails);

$route = ltrim(explode('?', $_SERVER['REQUEST_URI'])[0], '/');

    if ($route == '') {
        $page = $controllers['users']->home();
    } else {
        list($controllerName, $functionName) = explode('/', $route);
        $controller = $controllers[$controllerName];
        $page = $controller->$functionName();
    }

    $output = loadTemplate('../pages/' . $page['template'], $page['variables']);
    $title = $page['title'];
    require '../pages/layout.php';


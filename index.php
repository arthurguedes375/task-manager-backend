<?php

require __DIR__ . "/vendor/autoload.php";

header("Content-Type: application/json");

$router = new \CoffeeCode\Router\Router(URL_BASE);

$task = new \Source\Task\Task();


$router->group("");
$router->get("/", function () {
    $GLOBALS["router"]->redirect("frontend/index.html");

});

// Task
$router->group("/task");

// Create Task
$router->post("/create", function () {
    $paramTask = (!empty($_POST["task"] )) ? $_POST["task"] : null;
    $paramTaskDescription = (!empty($_POST["description"] )) ? $_POST["description"] : null;
    $paramTaskDate = (!empty($_POST["date"] )) ? $_POST["date"] : null;

    $createTask = $GLOBALS["task"]->createTask($paramTask, $paramTaskDescription, $paramTaskDate);
    if ($createTask === false || $paramTask == null || $paramTaskDescription == null || $paramTaskDate == null) {
        if (\Source\config\Connect::$fail) {
            http_response_code(500);
        }else {
            http_response_code(400);
        }
    }else {
        http_response_code(201);
        echo $createTask;
    }

});

// Edit
/*$router->put("", function () {


});*/




$router->dispatch();

if ($router->error()) {
    $router->redirect("/opt/{$router->error()}");
}
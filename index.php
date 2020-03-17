<?php

require __DIR__ . "/vendor/autoload.php";

$router = new \CoffeeCode\Router\Router(URL_BASE);

$task = new \Source\Task\Task();

$router->group("");
$router->get("/", function () {
    $GLOBALS["router"]->redirect("frontend/index.html");
});

$router->dispatch();

if ($router->error()) {
    $router->redirect("/opt/{$router->error()}");
}
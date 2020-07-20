<?php

require __DIR__ . "/vendor/autoload.php";

header("Content-Type: application/json");

// Routes

require __DIR__ . "/routes.php";

// ----------------------------------------------------------------


$router->dispatch();

if ($router->error()) {
    $router->redirect("/opt/{$router->error()}");
}
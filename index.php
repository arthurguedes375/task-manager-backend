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
$router->group("/tasks");

//Get Tasks

$router->get("/", function(){

    $tasks = $GLOBALS["task"]->selectAllTasks();

    if($tasks){
        echo($tasks);
    } else {
        $response['success'] = false;
        echo json_encode($response);
    }
});

//Get Task

$router->get("/{id}", function($request){
    
    $task = $GLOBALS["task"]->selectById($request['id']);

    if($task){
        echo($task);
    } else {
        $response['success'] = false;
        echo json_encode($response);
    }
});

// Store Task
$router->post("/create", function () {
    $paramTask = (!empty($_POST["task_title"] )) ? $_POST["task_title"] : null;
    $paramTaskDescription = (!empty($_POST["task_description"] )) ? $_POST["task_description"] : null;
    $paramTaskDate = (!empty($_POST["task_date"] )) ? $_POST["task_date"] : null;

    $createdTask = $GLOBALS["task"]->createTask($paramTask, $paramTaskDescription, $paramTaskDate);

    if ($createdTask === false || $paramTask == null || $paramTaskDescription == null || $paramTaskDate == null) {
        if (\Source\config\Connect::$fail) {
            http_response_code(500);
        }else if ($GLOBALS["task"]->fail == "duplicated") {
            http_response_code(406);
            $response['error'] = 'Task duplicated!';
            echo json_encode($response);
        }else {
            http_response_code(400);
            $response['error'] = 'Preencha todos os campos!!!';
            echo json_encode($response);
        }
    }else {
        http_response_code( 201 );

        //Return the last created task
        $createdTask = json_decode($createdTask);

        $task = $GLOBALS["task"]->selectById($createdTask->id);

        echo($task);

    }

});

//Update Task
$router->put("/update/{id}", function($request){
    $paramTask = (!empty($request["task_title"] )) ? $request["task_title"] : null;
    $paramTaskDescription = (!empty($request["task_description"] )) ? $request["task_description"] : null;
    $paramTaskDate = (!empty($request["task_date"] )) ? $request["task_date"] : null;

    $createdTask = $GLOBALS["task"]->editTask($request["id"], $paramTask, $paramTaskDescription, $paramTaskDate);

    if ($createdTask === false || $paramTask == null || $paramTaskDescription == null || $paramTaskDate == null) {
        if (\Source\config\Connect::$fail) {
            http_response_code(500);
        }else {
            http_response_code(400);
            $response['error'] = 'Preencha todos os campos!!!';
            echo json_encode($response);
        }
    }else {
        http_response_code(201);

        echo($createdTask);
    }
});


// Delete Task
$router->delete("/delete/{id}", function ($props) {

    $id = (!empty($props["id"])) ? $props["id"] : null;
    $deleteTask = $GLOBALS["task"]->deleteTask($props["id"]);

    if ($deleteTask === false || $id == null) {
        if (\Source\config\Connect::$fail) {
            http_response_code(500);
        }else {
            http_response_code(400);
            $response['error'] = 'Preencha todos os campos!!!';
            echo json_encode($response);
        }
    }else {
        http_response_code(200);
    }


});



$router->dispatch();

if ($router->error()) {
    $router->redirect("/opt/{$router->error()}");
}
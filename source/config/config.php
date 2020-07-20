<?php

define("URL_BASE", "https://localhost/task-manager-backend");


define("DATABASE", [
    "host" => "localhost",
    "dbname" => "taskmanager",
    "username" => "root",
    "password" => "",
    "options" => [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    ]
]);
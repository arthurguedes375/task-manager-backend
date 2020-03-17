<?php


namespace Source\Task;


use Source\config\Connect;
use PDO;
use PDOException;
use PDOStatement;

class Task
{


    /**
     * @var bool|false|PDO
     */
    private $db;


    /**
     * @var PDOException
     */
    public $fail;

    public function __construct ()
    {
        $this->db = Connect::getInstance();
    }

    /**
     * @param $task
     * @param $description
     * @param $when
     * @return bool|string
     */
    public function createTask ( $task, $description, $when )
    {
        $task = filter_var($task, FILTER_SANITIZE_SPECIAL_CHARS);
        $description = filter_var($description, FILTER_SANITIZE_SPECIAL_CHARS);
        $when = filter_var($when, FILTER_SANITIZE_SPECIAL_CHARS);

        try {
            $db = $this->db;
            $st = $db->prepare("INSERT INTO task (task, tdescription, twhen) VALUES (:task, :description, :when)");
            $st->bindValue(":task", $task, PDO::PARAM_STR);
            $st->bindValue(":description", $description, PDO::PARAM_STR);
            $st->bindValue(":when", $when, PDO::PARAM_STR);
            $st->execute();
            $result = $st->fetchAll();
            return json_encode($result);

        } catch (PDOException $exception) {
            $this->fail = $exception;
            return false;
        }
    }



}
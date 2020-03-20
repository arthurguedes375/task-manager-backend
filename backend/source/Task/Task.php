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
     * @param $date
     * @return bool|string
     */
    public function createTask ( $task, $description, $date )
    {
        $task = filter_var($task, FILTER_SANITIZE_SPECIAL_CHARS);
        $description = filter_var($description, FILTER_SANITIZE_SPECIAL_CHARS);
        $date = filter_var($date, FILTER_SANITIZE_SPECIAL_CHARS);

        try {
            $db = $this->db;
            $st = $db->prepare("INSERT INTO task (task, tdescription, tdate) VALUES (:task, :description, :date)");
            $st->bindValue(":task", $task, PDO::PARAM_STR);
            $st->bindValue(":description", $description, PDO::PARAM_STR);
            $st->bindValue(":date", $date, PDO::PARAM_STR);
            $st->execute();
            $result = ["id" => $db->lastInsertId()];
            return json_encode($result);

        } catch (PDOException $exception) {
            $this->fail = $exception;
            return false;
        }
    }

    public function selectById ( $id )
    {
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

        try {
            $db = $this->db;
            $st = $db->prepare("SELECT id, task, tdescription, tdate FROM task WHERE id = :id");
            $st->bindValue(":id", $id, PDO::PARAM_INT);
            $st->execute();
            if ($st->rowCount() > 0) {
                return json_encode($st->fetchAll());
            }else {
                return false;
            }

        }catch (PDOException $exception) {
            $this->fail = $exception;
            return false;
        }
    }

    public function selectAllTasks ()
    {

        try {
            $db = $this->db;
            $st = $db->prepare("SELECT id, task, tdescription, tdate FROM task");
            $st->execute();
            if ($st->rowCount() > 0) {
                return json_encode($st->fetchAll());
            }else {
                return false;
            }

        }catch (PDOException $exception) {
            $this->fail = $exception;
            return false;
        }

    }

    public function editTask ( $id, $task, $description, $date )
    {
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        $task = filter_var($task, FILTER_SANITIZE_SPECIAL_CHARS);
        $description = filter_var($description, FILTER_SANITIZE_SPECIAL_CHARS);
        $date = filter_var($date, FILTER_SANITIZE_SPECIAL_CHARS);

        try {

            $db = $this->db;
            $st = $db->prepare("UPDATE task SET task = :task, tdescription = :description, tdate = :date WHERE id = :id");
            $st->bindValue(":id", $id, PDO::PARAM_INT);
            $st->bindValue(":task", $task, PDO::PARAM_STR);
            $st->bindValue(":description", $description, PDO::PARAM_STR);
            $st->bindValue(":date", $date, PDO::PARAM_STR);
            $result = $st->execute();
            if ($result) {
                return $this->selectById($result);
            }else {
                return false;
            }

        }catch (PDOException $exception) {
            $this->fail = $exception;
            return $exception;
        }

    }



}
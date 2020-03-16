<?php


namespace Source\config;


use PDO;
use PDOException;
use PDOStatement;


/**
 * Class Connect
 * @package Source\config
 */
class Connect
{


    /**
     * @var string
     */
    private static $host = "localhost";
    /**
     * @var string
     */
    private static $dbname = "taskmanager";
    /**
     * @var string
     */
    private static $username = "root";
    /**
     * @var string
     */
    private static $password = "";
    /**
     * @var array
     */
    private static $options = [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    ];

    /**
     * @var
     */
    public $fail;

    /**
     * @var PDOStatement
     */
    private static $db;

    /**
     * Connect constructor.
     */
    public function __construct ()
    {
    }

    /**
     *
     */
    public function __clone ()
    {
    }


    /**
     * @return bool|PDOStatement |false
     */
    public function getInstance ()
    {
        if (empty(self::$db)) {
            try {
                $dsn = "mysql:dbname=". self::$dbname ."; host=". self::$host;
                self::$db = new PDO($dsn, self::$username, self::$password, self::$options);
                return self::$db;
            }catch (PDOException $exception) {
                return false;
            }
        }else {
            return self::$db;
        }
    }
}
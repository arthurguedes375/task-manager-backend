<?php


namespace Source\config;



require __DIR__ . '/../../vendor/autoload.php';

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
    private static $host = DATABASE["host"];
    /**
     * @var string
     */
    private static $dbname = DATABASE["dbname"];
    /**
     * @var string
     */
    private static $username = DATABASE["username"];
    /**
     * @var string
     */
    private static $password = DATABASE["password"];
    /**
     * @var array
     */
    private static $options = DATABASE["options"];

    /**
     * @var
     */
    public static $fail;

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
     * @return bool|PDO|false
     */
    public static function getInstance ()
    {
        if (empty(self::$db)) {
            try {
                $dsn = "mysql:dbname=". self::$dbname ."; host=". self::$host;
                self::$db = new PDO($dsn, self::$username, self::$password, self::$options);
                return self::$db;
            }catch (PDOException $exception) {
                self::$fail = $exception;
                return false;
            }
        }else {
            return self::$db;
        }
    }
}
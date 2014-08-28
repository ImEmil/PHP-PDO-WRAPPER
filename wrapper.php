<?php
class DB
{
    static $host = "localhost";
    static $db   = "myDatabaseName";
    static $user = "root";
    static $pass = "mypass123";

    # Don't touch!!1111
    private static $pdo;
    private static $pdoStatement;
    
    static function connect()
    {
        if(is_null(self::$pdo))
        {
             try
             {
                self::$pdo = new PDO(sprintf("mysql:host=%s;dbname=%s",
                    self::$host,
                    self::$dbname),
                    self::$user,
                    self::$pass,
                    array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
                    );
            }
            catch (PDOException $e)
            {
                exit($e->getMessage());
            }
        }
        else
            return new self;
    }

    static function prepare($query)
    {
        DB::connect();
        self::$pdoStatement = self::$pdo->prepare($query);
        return new self;
    }
    
    static function bindValue($name, $value, $type = PDO::PARAM_STR)
    {
        DB::connect();
        self::$pdoStatement->bindValue($name, $value, $type);
        return new self;
    }
    
    static function bindValues(array $binds)
    {
        DB::connect();
        foreach($binds as $valuesArray) {
            self::$bindValue($valuesArray[0], $valuesArray[1], (isset($valuesArray[2]) ? $valuesArray[2] : PDO::PARAM_STR));
        }
        return new self;
    }
    
    static function execute($data = array())
    {
        DB::connect();
        try {
            self::$pdoStatement->execute( isset($data) ? $data : null );
        }
        catch (PDOException $e) {
                die('Databas konflikt =>  ' . $e->getMessage());
        }
        return new self;
    }

    static function fetch($type = PDO::FETCH_BOTH)
    {
        DB::connect();
        return (self::$pdoStatement) ? self::$pdoStatement->fetch($type) : false;
    }
    
    static function fetchAll($type = PDO::FETCH_BOTH) 
    {
        DB::connect();
        return (self::$pdoStatement) ? self::$pdoStatement->fetchAll($type) : false;
    }

    static function query($query)
    {
        DB::connect();
        try {
            self::$pdoStatement = self::$pdo->query($query);
        }
        catch (PDOException $e) {
                die('Databas konflikt =>  ' . $e->getMessage());
        }
        return new self;
    }

    static function lastInsertId()
    {
        DB::connect();
        return self::$pdo->lastInsertId();
    }

    static function rowCount()
    {
        DB::connect();
        return (self::$pdoStatement) ? self::$pdoStatement->rowCount() : false;
    }
}


/*
 * USAGE
*/

$something = DB::prepare("SELECT * FROM `something`")->execute()->fetchAll();
var_dump($something);

<?php
/*
 *  PHP-PDO-WRAPPER
 *  ..................................................................
 *  Created & maintained by ImEmil @github (https://github.com/ImEmil)
 *  Distributed under MIT License
 *
 *  @version 2.0
*/

#if(!defined('IN_INDEX')) { die('Sorry, you cannot access this file.'); }

class DB
{
    private static $host    = "localhost";
    private static $dbname  = "mydatabase";
    private static $user    = "root";
    private static $pass    = "test123";
    private static $charset = "utf8";


    private static $dsn = "mysql:host=%s;dbname=%s;charset=%s";    // Dont touch it you twat
    private static $pdo, $statement;
    /**
     * __construct - Trying to reconnect to database if the current connection is empty.
     * 
     * @access public
     *
     * @return mixed Value.
     */
    public function __construct()
    {
        if(is_null(self::$pdo))
        {
            try
            {
                $attributes = [
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_ERRMODE          => PDO::ERRMODE_EXCEPTION
                ];
                $dsn       = sprintf(self::$dsn, self::$host, self::$dbname, self::$charset);
                self::$pdo = new PDO($dsn, self::$user, self::$pass, $attributes);
            }
            catch (PDOException $e)
            {
                exit($e->getMessage());
            }
        }
    }
    /**
     * prepare - Trying to prepare the query and store it inside the $statement variable.
     * 
     * @param string $sql Contains the sql query.
     *
     * @access public
     * @static
     *
     * @return obj
     */
    public static function prepare($sql)
    {
        try
        {
            self::$statement = self::$pdo->prepare($sql);
        }
        catch(PDOException $e)
        {
            exit("Preparing query error: {$e->getMessage()}");
        }
        return new self;
    }
    /*
    # USELESS!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    public static function bindValue($name, $value, $type = PDO::PARAM_STR)
    {
        self::$statement->bindValue($name, $value, $type);
        return new self;
    }
    
    public static function bindValues(array $binds)
    {
        foreach($binds as $valuesArray) {
            self::$bindValue($valuesArray[0], $valuesArray[1], (isset($valuesArray[2]) ? $valuesArray[2] : PDO::PARAM_STR));
        }
        return new self;
    }
    */
    /**
     * execute - Trying to run/execute the prepared sql query.
     * 
     * @param mixed $data Contains parameters for the sql query if any needs to be passed as an array or a single string which gets converted to an array later on.
     *
     * @access public
     * @static
     *
     * @return obj
     */
    public static function execute($params = array())
    {
        try
        {
        	$data = null;
        	
        	if(!empty($params))
        	{
	            if(!is_array($data))
	                $data = [$data];
	            else if(is_int($data))
	            	$data = ["{$data}"];
        	}

            self::$statement->execute($data);
        }
        catch (PDOException $e)
        {
            exit("Executing statement error: {$e->getMessage()}");
        }
        return new self;
    }
    /**
     * fetch - Trying to fetch a single/?/ record from the stored $statement variable.
     * 
     * @param constant $type Contains the format of the fetched results, usually an array 'ASSOC'.
     *
     * @access public
     * @static
     *
     * @return mixed Value.
     */
    public static function fetch($type = PDO::FETCH_ASSOC)
    {
        return self::$statement == true ? self::$statement->fetch($type) : false;
    }
    
    /**
     * fetchAll - Trying to fetch multiple/?/ records from the stored $statement variable.
     * 
     * @param constant $type Contains the format of the fetched results, usually an array 'ASSOC'.
     *
     * @access public
     * @static
     *
     * @return mixed Value.
     */
    public static function fetchAll($type = PDO::FETCH_ASSOC) 
    {
        return self::$statement == true ? self::$statement->fetchAll($type) : false;
    }
    /**
     * query - NO! You shouldn't use this!
     * 
     * @param mixed $query Gtfo.
     *
     * @access public
     * @static
     *
     * @return 0 fucks about your life.
     * You're literally a noob if you use this, and i'm a bigger one for letting this method exist.
     */
    public static function query($query)
    {
        try
        {
            self::$statement = self::$pdo->query($query);
        }
        catch (PDOException $e)
        {
            exit("Raw QUERY error: {$e->getMessage()}");
        }
        return new self;
    }
    /**
     * rowCount - Trying to receive the number of rows affected by the last SQL statement from our $statement variable.
     * 
     * @access public
     * @static
     *
     * @return int
     */
    public static function rowCount()
    {
        return self::$statement == true ? self::$statement->rowCount() : false;
    }
    /**
     * lastInsertId - Trying to receive the ID of the last inserted row
     * 
     * @access public
     * @static
     *
     * @return mixed Value.
     */
    public static function lastInsertId()
    {
        return self::$pdo->lastInsertId();
    }
}

/*


// Usage examples, initialize the database class
# $db = new DB();

// Example 1
# $testData = DB::prepare("SELECT * FROM `users` LIMIT 250")->execute(); // this way you dont need to use: global $db; inside your method :)
# $testData->fetchAll();

// Example 2
# $testData = $db::prepare("SELECT * FROM `users` LIMIT 250")->execute();
# $testData->fetchAll();

// Example 3
# $testData = $db->prepare("SELECT * FROM `users` LIMIT 250")->execute();
# $testData->fetchAll();

// Random.. ._.
# $testData = DB::prepare("SELECT * FROM `users` WHERE `username` = ?")
			->execute( intval($_GET['id']) )
			->fetch();


// echo "<pre>";
// print_r($testData);
// echo "</pre>";


*/

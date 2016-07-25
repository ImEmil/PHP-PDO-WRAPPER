<?php
/*
 *  PHP-PDO-WRAPPER
 *  ..................................................................
 *  Created & maintained by ImEmil @github (https://github.com/ImEmil)
 *  Distributed under MIT License
 *
 *  @version 2.0
*/

class DB
{
    protected static $host    = "localhost";
    protected static $dbname  = "vegoplanet";
    protected static $user    = "root";
    protected static $pass    = "";
    protected static $charset = "utf8";

    private static $dsn = "mysql:host=%s;dbname=%s;charset=%s";    // Dont touch it you twat
    private static $pdo, $statement;
    

    /**
     * getConnection - Trying to reconnect to database if the current connection is empty.
     * 
     * @access public
     * @static
     *
     * @return mixed Value.
     */
    public static function getConnection()
    {
        if(is_null(self::$pdo))
        {
            try
            {
    			$attributes = [
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_ERRMODE          => PDO::ERRMODE_EXCEPTION
                ];

                $dsn = sprintf(self::$dsn, self::$host, self::$dbname, self::$charset);

                self::$pdo = new PDO($dsn, self::$user, self::$pass, $attributes);
            }
            catch (PDOException $e)
            {
                exit($e->getMessage());
            }
        }
        else
            return new self;
    }

    /**
     * prepare - Trying to prepare the query and store it inside the $statement variable.
     * 
     * @param string $query Contains the sql query.
     *
     * @access public
     * @static
     *
     * @return obj
     */
    public static function prepare($query)
    {
        DB::getConnection();

        try
        {
            self::$statement = self::$pdo->prepare($query);
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
        DB::getConnection();
        self::$statement->bindValue($name, $value, $type);
        return new self;
    }
    
    public static function bindValues(array $binds)
    {
        DB::getConnection();
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
    public static function execute($data = array())
    {
        DB::getConnection();
        
        try
        {
            if(!is_array($data) && !empty($data)) // IM NEW =D
                $data = [$data]; // IM NEW =D

            self::$statement->execute( isset($data) ? $data : null );
        }
        catch (PDOException $e)
        {
            exit("Executing query error: {$e->getMessage()}");
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
        DB::getConnection();

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
        DB::getConnection();

        return self::$statement == true ? self::$statement->fetchAll($type) : false;
    }

    /**
     * query - fuckyou cuz you shouldn't use this!
     * 
     * @param mixed $query Gtfo.
     *
     * @access public
     * @static
     *
     * @return 0 fucks about your life.
     */
    public static function query($query)
    {
        DB::getConnection();

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
     * lastInsertId - Trying receive the number of rows affected by the last SQL statement from our $statement variable.
     * 
     * @access public
     * @static
     *
     * @return int
     */
    public static function lastInsertId()
    {
        DB::getConnection();

        return self::$pdo->lastInsertId();
    }

    /**
     * rowCount - Trying to receive 
     * 
     * @access public
     * @static
     *
     * @return mixed Value.
     */
    public static function rowCount()
    {
        DB::getConnection();

        return self::$statement == true ? self::$statement->rowCount() : false;
    }
}

/*
# PHP PDO Cheat Sheet

$users = DB::prepare("SELECT * FROM `users` WHERE `motto` = ?")
         ->execute( "Im new here" )
         ->fetchAll();

if($users->rowCount() > 0)
{
    // users existing...
}

# on progress.. more to come
*/

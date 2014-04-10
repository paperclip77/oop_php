<?php

require('MySQLResultSet.php');
require('MySQLException.php');

class MySQLConnect{
    
    const ONLY_ONE_INSTSANCE_ALLOWED = 5000;
    
    private $connection;
    private static $instances = NULL;
    /*
    STATIC = If one instance changes the value of a static variable, it is changed for all instances
    */
    
    private function __construct($hostname, $username, $password){
        if(!$this->connection = mysql_connect($hostname,$username,$password)){
            throw new MySQLException(mysql_error(), mysql_errno());
        }
    }
    
    static public function getInstance($hostname, $username, $password){
        if(self::$instances == NULL){
            self::$instances = new MySQLConnect($hostname, $username, $password);
            return self::$instances;
        } else {
            $msg = "Close the existing instance of the MySQLConnect class";
            throw new MySQLException($msg, self::ONLY_ONE_INSTSANCE_ALLOWED);
        }
    }
    
    public function __destruct(){
        $this->close();
    }
    
    public function createResultSet($strSQL, $databasename){
        $rs = new MySQLResultSet($strSQL, $databasename, $this->connection);
        return $rs;
    }
    
    public function close(){
        MySQLConnect::$instances = 0;
        if(isset($this->connection)){
            mysql_close($this->connection);
            unset($this->connection);
        }
    }
    
    
}
?>
<?php
class MySQLException extends Exception{
    
    public function __construct($message, $errorno){
        //check for programmer error
        if($errorno >= 5000){
            $message = __CLASS__." type. Improper class usage. ".$message;
        } else {
            $message = __CLASS__." - ".$message;
        }
        
        parent::__construct($message, $errorno);
    }
    
    //override __toString
    public function __toString(){
        return ("Error: ".$this->code." - ".$this->message);
    }
}
?>
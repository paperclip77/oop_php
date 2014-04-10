<?php
class MySQLResultSet implements Iterator{
    private $strSQL;
    private $databasename;
    private $connection;
    private $result;
    
    //interface vars
    private $currentrow;
    private $valid;
    private $key;
    
    public function __construct($strSQL, $databasename, $connection){
        $this->strSQL = $strSQL;
        $this->connection = $connection;
        $this->databasename = $databasename;
        mysql_selectdb($databasename, $connection) or die(mysql_error()." Error no:".mysql_errno());
        $this->result = mysql_query($strSQL, $connection) or die(mysql_error()." Error no:".mysql_errno());
        if(stristr($strSQL,"SQL_CALC_FOUND_ROWS")){
            $msg="No need to use SQL_CALC_FOUND_ROWS";
            die($msg);
        }
    }
    
    //INTERFACE METHODS
    public function current(){
        return $this->currentrow;
    }
    
    public function key(){
        return $this->key;
    }
    
    public function valid(){
        return $this->valid;
    }
    
    public function next(){
        if($this->currentrow = mysql_fetch_array($this->result)){
            $this->valid = true;
            $this->key++;
        } else {
            $this->valid = false;
        }
    }
    
    public function rewind(){
        if(mysql_num_rows($this->result) > 0){
            if(mysql_data_seek($this->result, 0)){
                $this->valid = true;
                $this->key = 0;
                $this->currentrow = mysql_fetch_array($this->result);
            }
        } else {
            $this->valid = false;
        }
    }
    
    //END OF INTERFACE METHODS
    
    public function __destruct(){
        $this->close();
    }
    
    public function getRow(){
        return mysql_fetch_array($this->result);
    }
    
    public function getDatabaseName(){
        return $this->databasename;
    }
    
    public function getNumberColumns(){
        return mysql_num_fields($this->result);
    }
    
    public function getNumberRows(){
        return mysql_num_rows($this->result);
    }
    
    public function getInsertId(){
        return mysql_insert_id($this->connection);
    }
    
    public function getUnlimitedNumberRows(){
        $number = 0;
        $versionnumber = $this->findVersionNumber();
        $version = substr($versionnumber,0,1);
        if(!$this->checkForSelect()){
            $msg = "Illegal method call - not a SELECT query";
            die($msg);
        }
        
        $tempsql = strtoupper($this->strSQL);
        $end = strpos($tempsql, "LIMIT");
        if($end===false){
            $number = mysql_num_rows($this->result);
        } else if($version < 4){
            $number = $this->countVersionThree($end);
        } else {
            $number = $this->countVersionFour();
        }
        
        return $number;
    }
    
    public function getFieldNames(){
        $fieldnames = array();
        if(isset($this->result)){
            $num = mysql_numfields($this->result);
            for($i=0;$i<$num;$i++){
                $meta = mysql_fetch_field($this->result, $i) or die(mysql_error()." Error no:".mysql_errno());
                $fieldnames[$i] = $meta->name;
            }
        }
        return $fieldnames;
    }
    
    public function findVersionNumber(){
        return mysql_get_server_info($this->connection);
    }
    
    //private methods
    private function checkForSelect(){
        $bln = true;
        $strtemp = trim(strtoupper($this->strSQL));
        if(substr($strtemp,0,6)!="SELECT"){
            $bln = false;
        }
        return $bln;
    }
    
    private function close(){
        if(isset($this->result)){
            mysql_free_result($this->result);
            unset($this->result);
        }
        
    }
    
    //version specific count methods
    private function countVersionFour(){
        $tempsql = trim($this->strSQL);
        $insertstr = " SQL_CALC_FOUND_ROWS ";
        $tempsql = substr_replace($tempsql, $insertstr, 6, 1);
        $rs = mysql_query($tempsql, $this->connection) or die(mysql_error()." Error no:".mysql_errno());
        $tempsql = "SELECT FOUND_ROWS()";
        $rs = mysql_query($tempsql) or die(mysql_error()." Error no:".mysql_errno());
        $row = mysql_fetch_row($rs);
        $number = $row[0];
        //dispose of $rs
        mysql_free_result($rs);
        return $number;
    }
    
    private function countVersionThree($end){
        $tempsql = strtoupper($this->strSQL);
        if(!strpos($tempsql,"DISTINCT")){
            //create recordset
            $start= strpos($tempsql,"FROM");
            $numchars=$end-$start;
            $countsql="SELECT COUNT(*) ";
            $countsql.=substr($this->strSQL, $start, $numchars);
            $rs=mysql_query($countsql, $this->connection) or
            die ( mysql_error(). " Error no:".mysql_errno());
            $row=mysql_fetch_row($rs);
            $number=$row[0];
            //dispose of $rs
            mysql_free_result($rs);          
  	}else{         
            $msg="Using keyword DISTINCT, calculate total number manually.";
	    die($msg);
        }
        return $number;
    }
}
?>
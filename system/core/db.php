<?php

/**
 * DB class for some basic simpilification in future odbc functions will be here however for ORM class nothing will change
 * @author Ervin
 */
class db{
    /**
     * clenske promene
     */
    protected $user;
    protected $pass;
    protected $database;
    protected $address;
    protected $link;
    protected $db_selected;
    
    /**
     * konstruktor
     */
    public function __construct($adr=NULL,$usr=NULL,$pass=NULL,$db=NULL) {
        if($adr==NULL){$this->address=_DB_ADDRESS;}else{$this->address=$adr;}
        if($usr==NULL){$this->user=_DB_USER;}else{$this->user=$usr;}
        if($pass==NULL){$this->pass=_DB_PASS;}else{$this->pass=$pass;}
        if($db==NULL){$this->database=_DB_DATABASE;}else{$this->database=$db;}
    }
    
    /**
     * metody
     */
    
    public static function makeSafe($str)
    {
        return mysql_real_escape_string(strip_tags(stripcslashes($str)));
    }
    
    public function conect(){
        
        $this->link=mysql_connect($this->address, $this->user, $this->pass);
        $this->db_selected=mysql_select_db($this->database) or
            die("Could not select database: " . mysql_error());
        mysql_query("SET CHARACTER SET utf8");
        
        if(!$this->link){
            die("Could not connect: " . mysql_error());
            return false;
        }
        elseif(!$this->db_selected){
            die("Could not select database: " . mysql_error());
            return false;
        }
        else{return $this->link;}
    }
    
    public function query($sql,$mode){
        $result=mysql_query($sql, $this->link);
        return $result;
    }
    
    public function fetch_array($result){
        if(is_resource($result)){
            $data=mysql_fetch_array($result);
            return $data;
        }
        return FALSE;
    }
    
    public function cleanUp($result) {
        //nothing here this is dumy function to prevent errors in PDO its requred by ORM class
        return TRUE;
    }
    
    public function num_rows($result){
        $row=mysql_num_rows($result);
        return $row;
    }
}

//end of file db.php

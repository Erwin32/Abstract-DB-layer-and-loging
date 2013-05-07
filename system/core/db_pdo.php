<?php

/**
 * DB class for some basic simpilification in future odbc functions will be here however for ORM class nothing will change
 * @author Ervin
 */
class db_pdo{
    /**
     * clenske promene
     */
    protected $user;
    protected $pass;
    protected $database;
    protected $address;
    protected $db_selected;
    protected $pdo;


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
        return strip_tags(stripcslashes($str));
    }
    
    
    
    public function conect(){
        
        $this->pdo=new PDO('mysql:host='.$this->address.';dbname='.$this->database.';charset=utf8', $this->user, $this->pass);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        if(!is_object($this->pdo)){
            die("Connection to MySQL Database canot be established!");
            return false;
        }
        else{return $this->pdo;}
    }
    
    public function query($sql,$mode='exec'){
        
        if($mode=='select'){
            try {
            $result=$this->pdo->query($query);
            } catch(PDOException $ex) {
                //error handle
                log::writeLogEntry('PDO error - '.$ex->getMessage());
                return FALSE;
            }   
        }
        else{
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $stmt->closeCursor();
            return TRUE;
        }
        
        return $result;
    }
    
    public function fetch_array($result){
        if(is_object($result)){
            $data= $result->fetch(PDO::FETCH_ASSOC);
            return $data;
        }
        
        return FALSE;
    }
    
    public function cleanUp($result) {
        $result->closeCursor();
        return TRUE;
    }
    
    public function num_rows($result){
        //disabled
        return FALSE;
    }
}

//end of file db.php

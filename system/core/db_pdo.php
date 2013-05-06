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
        
        echo 'Tring Query:'.$sql.'<br>in mode '.$mode.'<br>';
        if($mode=='select'){
            echo 'select query inside if<br>';
            try {
            $result=$this->pdo->query($query);
            } catch(PDOException $ex) {
                //error handle
                log::writeLogEntry('PDO error - '.$ex->getMessage());
                return FALSE;
            }   
        }
        else{
            echo 'other query inside else<br>';
            $this->pdo->exec($sql);
            //here I need to close cursor 
            return TRUE;
        }
        
        return $result;
    }
    
    public function fetch_array($result){
        if(is_object($result)){
            $data= $result->fetch(PDO::FETCH_ASSOC);
            $result->closeCursor();
            return $data;
        }
        
        return FALSE;
    }
    
    public function num_rows($result){
        //disabled
        return FALSE;
    }
}

//end of file db.php

<?php

/**
 * DB class for some basic simpilification in future odbc functions will be here however for ORM class nothing will change
 * @author Ervin
 */
class db{
    /**
     * clenske promene
     */
    protected static $user;
    protected static $pass;
    protected static $database;
    protected static $address;
    protected static $link;
    protected static $db_selected;
    
    /**
     * konstruktor
     */
    public function __construct() {
        
    }
    
    /**
     * metody
     */
    
    public static function init() {
        self::$address=_DB_ADDRESS;
        self::$user=_DB_USER;
        self::$pass=_DB_PASS;
        self::$database=_DB_DATABASE;
    }


    public static function makeSafe($str)
    {
        return mysql_real_escape_string(strip_tags(stripcslashes($str)));
    }
    
    public static function conect(){
        
        self::$link=mysql_connect(self::$address, self::$user, self::$pass);
        self::$db_selected=mysql_select_db(self::$database) or
            die("Could not select database: " . mysql_error());
        mysql_query("SET CHARACTER SET utf8");
        
        if(!self::$link){
            die("Could not connect: " . mysql_error());
            return false;
        }
        elseif(!self::$db_selected){
            die("Could not select database: " . mysql_error());
            return false;
        }
        else{return self::$link;}
    }
    
    public static function query($sql){
        $result=mysql_query($sql, self::$link);
        return $result;
    }
    
    public static function fetch_array($result){
        if(is_resource($result)){
            $data=mysql_fetch_array($result);
            return $data;
        }
        return FALSE;
    }
    
    public static function num_rows($result){
        $row=mysql_num_rows($result);
        return $row;
    }
}

//end of file db.php

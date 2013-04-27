<?php
/**
 * Description of log
 * Static class for writing any kind of txt events into DB log
 * @author Ervin
 */
class log {
    /**
     * Varialble for ORM instance
     */
    
    protected static $orm;
    
    /**
     * Methods
     */
    
    /**
     * Inicializes ORM in "handle mode" for logs
     */
    public static function init($db) {
        self::$orm=new orm_log($db);
    }
    
    /**
     * Determins human readable timestamp for log entry and creates log entry in DB accepting any string to log
     * @param type $msg
     */
    public static function writeLogEntry($msg) {
        self::$orm->checkForTable(TRUE);   
        $time=time();
        $timeStamp=date("[H:i:s]", $time);
        self::$orm->set('msg', $timeStamp.$msg, TRUE);
    }
    
    
}

//end of file log.php

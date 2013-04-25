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
    
    public static function init() {
        self::$orm=new orm_log();
    }
    
    public static function writeLogEntry($msg) {
        echo self::$orm->state(TRUE);
        $time=time();
        $timeStamp=date("[H:i:s]", $time);
        self::$orm->set('msg', $timeStamp.$msg, TRUE);
        echo $msg;
        echo self::$orm->state(TRUE);
    }
    
    
}

//end of file log.php

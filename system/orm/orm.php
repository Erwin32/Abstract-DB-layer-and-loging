<?php

/**
 * Description of orm
 * Base ORM class for extending
 * @author Ervin
 */
class orm {
    
    /**
     * Variables
     */
    
    /**
     * fields array holds row data
     * @var type 
     */
    protected $fields=array();
    /**
     * keyField holds name of field wich is primary key (defined in extended ORM class)
     * @var type 
     */
    protected $keyField;
    /**
     * key holds primary key for target table
     * @var type 
     */
    protected $key;
    /**
     * loaded holds ORM status 1 if data is loaded form DB, 0 if its empty, 2 if data is desynchronized(before saving)
     * @var type 
     */
    protected $loaded=0;
    /**
     * table holds name of the table in question (defined in extended ORM class)
     * @var type 
     */
    protected $table;
    /**
     * schema holds data used for table creation (defined in extended ORM class)
     * @var type 
     */
    protected $schema=array();
    
    
    
    /**
     * Construct
     * @param type $key
     */
    public function __construct($key=NULL) {
        $this->key=$key;
        if($key!=NULL){
            $this->load();
        }
    }
    
    /**
     * Methods
     */
    
    /**
     * loads data from DB
     * @param type $key
     * @return \orm
     */
    public function load($key='none'){
        if('none'!==$key){
            $this->key=$key;
        }
        if(empty($this->keyField)){
            $logStatement='ORM Load failed in '.get_class($this).' Primary Key is empty!';
            log::writeLogEntry($logStatement);
            return $this;
        }
        $sql="SELECT * FROM $this->table";
        $result=db::query($sql);
        $this->fields=db::fetch_array($result);
        if($this->fields==FALSE){
            $logStatement='ORM Load failed in '.get_class($this).' SELECT failed!';
            log::writeLogEntry($logStatement);
            $this->unload();
            return $this;
        }
        $this->loadExtraWork();
        $this->loaded=1;
        
    return $this;
    }
    
    /**
     * Gets field value
     * @param type $what
     * @return type
     */
    public function get($what) {
        return $this->fields[$what];
    }
    
    /**
     * Sets Data to variable representing that DB colum for row loaded in ORM not trigering actual save to FB by default
     * @param type $what
     * @param type $update
     * @return \orm
     */
    public function set($what, $val, $update=FALSE){
        $val=db::makeSafe($val);
        $this->fields[$what]=$val;
        
        if(1==$this->loaded or 2==$this->loaded){
            $this->loaded=2;
        }
        else {
            $this->loaded=3;
        }
        echo $this->state(TRUE);
        
        if($update){
            $this->save();
        }
        
        return $this;
    }
    
    /**
     * Returns state of ORM as int or as str(default is int)
     * @param type $verbal
     * @return string or int
     */
    public function state($verbal=FALSE){
        if($verbal){
            switch ($this->loaded){
            case 0:
                return 'Empty(unloaded)';
                break;
            case 1:
                return 'Synchronized(loaded)';
                break;
            case 2:
                return 'Desynchronized(loaded)';
                break;
            case 3:
                return 'Desynchronized(New row data)';
                break;
            }
        }
        else {
            return $this->loaded;
        }
    }
    
    /**
     * Unloads data and resets ORM to not loaded state
     * @return \orm
     */
    public function unload() {
        
        $this->fields=array();
        $this->loaded=0;
        unset($this->key);
        
        return $this;
    }
    
    /**
     * Deletes Row to wich it tied to from table first param states if deleteExtraWork method shuld be caled for user specified clean up, second param if true will unload data from ORM otherwise you still have soft backup loaded in orm class
     * @param type $hasChildsOrParrents
     * @param type $andUnload
     * @return \orm
     */
    public function delete($hasChildsOrParrents=FALSE,$andUnload=FALSE) {
        $sql="DELETE FROM $this->table WHERE $this->keyField=$this->key LIMIT 1;";
        db::query($sql);
        $this->loaded=3;
        if($hasChildsOrParrents){
            $this->deleteExtraWork();
        }
        
        if($andUnload){
            $this->unload();
        }
        
        return $this;
    }

    /**
     * saves current data from array to DB
     */
    public function save(){
        echo '<br>SAVING<br>';
        echo $this->fields['msg'];
        if($this->loaded==1 OR $this->loaded==2){
            if($this->loaded==1){
                //zapis do logu
            }
            else {
                $sql="UPDATE $this->table SET ";
                foreach ($this->fields as $key => $value) {
                    $sql.=" $key='$value',";
                }
                $sql=substr_replace($sql ,"",-1);
                $sql.="WHERE $this->key=some_value";
            }
        }
        else {
            $keys='';
            $vals='';
            foreach ($this->fields as $key => $value) {
                $keys.=$key.',';
                $vals.='\''.$value.'\''.',';
            }
            $keys=substr_replace($keys ,"",-1);
            $vals=substr_replace($vals ,"",-1);
            $sql="INSERT INTO $this->table ($keys) VALUES($vals)";
        }
        
        echo '<br>'.$sql.'<br>';
        
        db::query($sql);
        $this->loaded=1;
    }
    
    /**
     * checks for table existance returns true if table is present if first param is true it will create specified table if table dont exist
     * @param type $ifNotCreateIt
     * @return boolean
     */
    public function checkForTable($ifNotCreateIt=FALSE) {
        $result=db::query("SHOW TABLES LIKE '$this->table'");
        
           $prep=db::fetch_array($result);
           if($prep==FALSE){
               if($ifNotCreateIt){
                   $this->create();
               }
               else {
                   return FALSE;
               }
           }
           else {
               return true;
           }  
    }
    
    /**
     * Creates table acording to schema not overwriting existing one unles first param is true
     * @param type $force
     */
    protected function create($force=FALSE) {
        if($force){$sqlForcePart="";}else{$sqlForcePart="IF NOT EXISTS";}
        
        $sql="CREATE TABLE $sqlForcePart `$this->table` (";
        foreach ($this->schema as $key => $value) {
            switch ($this->schema[$key]['type']){
                case 'int':
                    $typeAndLimit='int('.$this->schema[$key]['limit'].')';
                    break;
                case 'varchar':
                    $typeAndLimit='varchar('.$this->schema[$key]['limit'].')';
                    break;
                case 'text':
                    $typeAndLimit='text';
                    break;
                default:
                    $typeAndLimit=$this->schema[$key]['type'].'('.$this->schema[$key]['limit'].')';
                    break;
            }
            
            if ($this->schema[$key]['null']==0){$null='NOT NULL';}else{$null='';}
            if ($this->schema[$key]['key']==1){$auto='AUTO_INCREMENT';$primary=$this->schema[$key]['name'];}else{$auto='';}
            
            $sql.='`'.$this->schema[$key]['name'].'` '.$typeAndLimit.' '.$null.' '.$auto.',';
        }
        
        $sql.='PRIMARY KEY (`'.$primary.'`)';
        $sql.=') ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;';
        
        db::query($sql);
    }

    /**
     * place for special script that shuld be executed after selecting data
     */
    protected function loadExtraWork(){
        
    }
    
    /**
     * place for special script that shuld be executed before saving data
     */
    protected function saveExtraWork(){
        
    }
    
    /**
     * place for special script that is executed when delete is caled whit first param true
     */
    protected function deleteExtraWork() {
        
    }
}

//end of file orm.php

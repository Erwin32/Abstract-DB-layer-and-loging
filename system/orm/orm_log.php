<?php
/**
 * Description of orm_log
 *
 * @author Ervin
 */
class orm_log extends orm{

    public function __construct($key=NULL) {
        $this->keyField='id';
        $this->table='log__'.date("d_m_Y");
        
        $this->schema[0]['name']='id';
        $this->schema[0]['type']='int';
        $this->schema[0]['limit']=11;
        $this->schema[0]['null']=0;
        $this->schema[0]['key']=1;
        $this->schema[1]['name']='msg';
        $this->schema[1]['type']='text';
        $this->schema[1]['limit']='';
        $this->schema[1]['null']=0;
        $this->schema[1]['key']=0;
        
        $this->checkForTable(TRUE);
        
        parent::__construct($key);
    }
}

//end of file orm_log.php

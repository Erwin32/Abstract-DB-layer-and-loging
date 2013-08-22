<?php

class container {
    private $objects;
    public function __get($class){
        if(isset($this->objects[$class])){
            return $this->objects[$class];
        }
        return $this->objects[$class] = new $class();
    }
}

//end of file
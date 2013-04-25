<?php

function autoload_classes()
{
    $dir=_REALPATH.'system/core/';
	//if directory exist go trouh it an load up every file exept this one
    if (is_dir($dir)) {
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if($file!='.' AND $file!='..' AND $file!='autoload.php')
                {
                    require_once(_REALPATH.'system/core/'.$file);
                }
                
            }
            closedir($dh);
        }
    }
    $dir=_REALPATH.'system/orm/';
	//if directory exist go trouh it an load up every file exept this one
    if (is_dir($dir)) {
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if($file!='.' AND $file!='..' AND $file!='autoload.php')
                {
                    require_once(_REALPATH.'system/orm/'.$file);
                }
                
            }
            closedir($dh);
        }
    }
}

//end of file autoload.php

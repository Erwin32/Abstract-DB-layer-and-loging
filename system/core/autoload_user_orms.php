<?php

function autoload_orms()
{
    $dir=_REALPATH.'app/orm/';
	//if directory exist go trouh it an load up every file exept this one
    if (is_dir($dir)) {
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if($file!='.' AND $file!='..' AND $file!='autoload.php')
                {
                    echo "filename: $file : filetype: " . filetype($dir . $file) . " LOADED<br>";
                    require_once(_REALPATH.'system/core/'.$file);
                }
                
            }
            closedir($dh);
        }
    }
}

//end of file autoload.php

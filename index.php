<?php

//determine path to document root
$system_path='./';
if (realpath($system_path) !== FALSE)
	{
		$system_path = realpath($system_path).'/';
	}
$system_path = rtrim($system_path, '/').'/';

define('_REALPATH', $system_path);

//initialize frame
require_once(_REALPATH.'system/autoload.php');
autoload_classes();
//get general config file
require_once(_REALPATH.'app/conf/general-config.php');

//init DB
$db1=new db;
$db1->conect();
log::init($db1);

//connect your defined ORMs
autoload_orms();

//so we are ready lets swich to main.php where you can do what ever you want
require_once(_REALPATH.'app/main.php');

//end of file index.php

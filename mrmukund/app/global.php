<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
set_include_path(dirname(__FILE__)); # include path - don't change

include_once 'config.php';
include_once 'view/helper.php';

function __autoload($class_name) {
	require_once 'model/'.$class_name.'.class.php';
}
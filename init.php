<?php
/**
 * Created by JetBrains PhpStorm.
 * User: roman
 * Date: 28.08.11
 * Time: 11:15
 * To change this template use File | Settings | File Templates.
 */

function __autoload( $className )
{
	if( class_exists($className) )
		return;

	$dirs = array( '', 'system/', 'modules/' );

	foreach( $dirs as $dir )
	{
		if( file_exists("classes/" . $dir . $className . ".php") )
		{
			require "classes/" . $dir . $className . ".php";
			return;
		}
	}
	throw new Exception( "Класс {$className} не найден" );
}

function exception_handler( $exception )
{
    echo "{$exception}\n";
}
set_exception_handler("exception_handler");

function error_handler( $errno, $errstr, $errfile, $errline )
{
    echo "Error code {$errno}: {$errstr} in file {$errfile}, line {$errline}\n";
}
set_error_handler("error_handler");

require_once "config/sysconfig.php";
require_once "config/DBconfig.php";

session_start();

?>
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
    require_once 'classes/' . $className . '.php';
}

function exception_handler( $exception )
{
    echo $exception;
}
set_exception_handler('exception_handler');

function error_handler( $errno, $errstr, $errfile, $errline )
{
    echo "{$errno}, {$errstr}, {$errfile}, {$errline}";
}
set_error_handler("CustomErrorHandler");

session_start();

?>
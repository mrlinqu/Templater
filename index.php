<?php
/**
 * Created by JetBrains PhpStorm.
 * User: r.khuramshin
 * Date: 26.08.11
 * Time: 10:09
 */

error_reporting(E_ALL);
ini_set('display_errors','On');

require_once 'init.php';

$templ_vars = array('simpleVar'=>'sipleValue', 'simpleDate'=>new DateTime('now', new DateTimeZone('Europe/Moscow')));

echo Templater::render( 'main.html', $templ_vars );


?>
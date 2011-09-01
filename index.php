<?php
/**
 * Created by JetBrains PhpStorm.
 * User: r.khuramshin
 * Date: 26.08.11
 * Time: 10:09
 */
require_once 'init.php';

$templ_vars = array('simpleVar'=>'sipleValue', 'simpleDate'=>new DateTime('now', new DateTimeZone('Europe/Moscow')));

echo Templater::render( 'page1.html', $templ_vars );


?>
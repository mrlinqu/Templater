<?php
/**
 * Created by JetBrains PhpStorm.
 * User: r.khuramshin
 * Date: 26.08.11
 * Time: 10:09
 */
require_once 'init.php';

$tmpl = Singleton::getInstance( 'Templater' );

$templ_vars = array('simpleVar'=>'sipleValue', 'simpleDate'=>new DateTime('now', new DateTimeZone('Europe/Moscow')));

echo $tmpl->parse( 'page1.html', $templ_vars );


?>
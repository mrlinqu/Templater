<?php
/**
 * Created by JetBrains PhpStorm.
 * User: r.khuramshin
 * Date: 26.08.11
 * Time: 10:09
 */

error_reporting(E_ALL);
ini_set('display_errors','On');

$sys__startTime = microtime(true);

require_once 'init.php';

echo Templater::render( 'main.html' );

echo sprintf("\n<!-- Generated in %.04f seconds -->\n", microtime(true) - $sys__startTime);

if( isset(DB_BENCH) )
	echo sprintf("\n<!-- DB queries %d in %.04f seconds -->\n", DB::$queryCount, DB::$queryTime );

?>
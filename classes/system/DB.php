<?php
/**
 * Created by JetBrains PhpStorm.
 * User: roman
 * Date: 28.08.11
 * Time: 19:58
 * To change this template use File | Settings | File Templates.
 */
 
class DB
{
	protected $dblink = null;
	public static $queryTime = 0;
	public static $queryCount = 0;

    public function __construct()
    {
        $this->dblink = new mysqli( DB_HOST, DB_USER, DB_PASSWD, DB_DBASE);

		if( mysqli_connect_errno() )
			throw new Exception( "Проблемы с базой данных" );
			//printf("Ошибка подключения: %s\n", mysqli_connect_error());

		//$mysqli->close();
    }
	
    public function query( $queryString )
    {
	    if( defined("DB_BENCH") )
	    {
		    self::$queryCount++;
		    $db__startTime = microtime(true);
	    }

	    if( $result = $this->dblink->query( $queryString ) )
	    {
		    $arr = $result->fetch_all();
		    $result->close();

		    if( defined("DB_BENCH") )
		        self::$queryTime += microtime(true) - $db__startTime;

		    return $arr;
	    }else{
		    if( defined("DB_BENCH") )
		        self::$queryTime += microtime(true) - $db__startTime;

		    return false;
	    }

    }
    public function query_row( $queryString )
    {
	    if( defined("DB_BENCH") )
	    {
		    self::$queryCount++;
		    $db__startTime = microtime(true);
	    }

	    if( $result = $this->dblink->query( $queryString ) )
	    {
		    $arr = $result->fetch_array();
		    $result->close();

		    if( defined("DB_BENCH") )
		        self::$queryTime += microtime(true) - $db__startTime;

		    return $arr;
	    }else{
		    if( defined("DB_BENCH") )
		        self::$queryTime += microtime(true) - $db__startTime;

		    return false;
        }
    }
    public function query_field( $queryString )
    {
	    if( defined("DB_BENCH") )
	    {
		    self::$queryCount++;
		    $db__startTime = microtime(true);
	    }

	    if( $result = $this->dblink->query( $queryString ) )
	    {
		    $row = $result->fetch_row();
		    $result->close();

		    if( defined("DB_BENCH") )
		        self::$queryTime += microtime(true) - $db__startTime;

		    return $row[0];
	    }else{
		    if( defined("DB_BENCH") )
		        self::$queryTime += microtime(true) - $db__startTime;

		    return false;
	    }
    }
}

?>
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
	protected $dbconnect = null;

    public function __construct()
    {
        $this->dbconnect = new mysqli( DB_HOST, DB_USER, DB_PASSWD, DB_DBASE);

		if( mysqli_connect_errno() )
			throw new Exception( "Проблемы с базой данных" );
			//printf("Ошибка подключения: %s\n", mysqli_connect_error());

		//$mysqli->close();
    }
	
    public function query(){}
    public function onerow_query(){}
    public function onefield_query(){}
}

?>
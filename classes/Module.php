<?php
/**
 * Created by JetBrains PhpStorm.
 * User: r.khuramshin
 * Date: 29.08.11
 * Time: 9:54
 * To change this template use File | Settings | File Templates.
 */

define( "FLD_STRING",   "String"   );
define( "FLD_TEXT",     "Text"     );
define( "FLD_INT",      "Int"      );
define( "FLD_FLOAT",    "Float"    );
define( "FLD_CURRENCY", "Currency" );
define( "FLD_DATE",     "Date"     );
define( "FLD_DATETIME", "DateTime" );
define( "FLD_ARRAY",    "Array"    );
define( "FLD_TABLE",    "Table"    );
define( "FLD_OBJECT",   "Object"   );

class Module
{
	protected $db;
	protected $msg;
	protected $fields = array();
	protected $dbTable = '';
	const delayedRender = false;

	public function __construct()
	{
		$this->db = Singleton::getInstance( 'DB' );
		$this->msg = Singleton::getInstance( 'SystemMessages' );
		$this->newField( FLD_INT, 'id', 'ID' );
		$this->initByPOST();
	}

	public function __get( $name )
	{
		if( !isset($fields[$name]) )
			return null;
		
		return $fields[$name]->getValue();
	}

	public  function __set( $name, $value )
	{
		if( !isset($fields[$name]) )
			throw new Exception( "Попытка установить значение несуществующего поля {$name} у объекта ".get_class($this) );

		$fields[$name]->setValue( $name, $value );
	}

	protected function initByPOST()
	{
		if( !isset($_POST) || empty($_POST) )
			return;

		$prefix = get_class($this);
		foreach( $this->fields as $fieldname => $field )
		{
			if( isset($_POST[$prefix.'_'.$fieldname]) )
			{
				$field->setValue( $_POST[$prefix.'_'.$fieldname] );
			}
		}
	}

	protected function newField( $type, $name, $caption, $properties=array() )
	{
		if( isset($fields[$name]) )
			throw new Exception( "Повторное определение поля {$name} у объекта ".get_class($this) );

		$className = 'Field'.$type;
		$this->fields[$name] = new $className( $name, $caption, $properties );
		$this->fields[$name]->namePrefix = get_class($this);
	}

	protected function resetToDefault()
	{
		foreach( $this->fields as $field )
		{
			$field->resetToDefault();
		}
	}

	protected function loadFromArray( $array )
	{
		foreach( $this->fields as $fieldname => $field )
		{
			$field->setValue( $array[$fieldname] );
		}
	}

	protected function loadByQuery( $queryString )
	{
		$row = $this->db->onerow_query( $queryString );
		if( $row ){
			$this->loadFromArray( $row );
		}else{
			$this->resetToDefault();
		}
	}

	protected function loadById( $id )
	{
		if( $this->dbTable )
		{
			$this->loadByQuery( "select * from {$this->dbTable} where id = {$id}" );
		}
	}

	public function getField( $fieldName )
	{
		return $this->fields[$fieldName];
	}

	public function getHTML()
    {
        return '';
    }
}

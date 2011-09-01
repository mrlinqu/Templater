<?php
/**
 * Created by JetBrains PhpStorm.
 * User: r.khuramshin
 * Date: 29.08.11
 * Time: 9:54
 * To change this template use File | Settings | File Templates.
 */
 
class Module
{
	protected $db  = Singleton::getInstance( 'DB' );
	protected $msg = Singleton::getInstance( 'SystemMessages' );
	protected $fields = array();
	protected $dbTable = '';

	public function __construct()
	{
		$this->newField( FLD_INT, 'id', null, 'ID' );
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
		if( !isset($_POST) || !count($_POST) )
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

	protected function newField( $type, $name, $caption, &$properties=array() )
	{
		if( isset($fields[$name]) )
			throw new Exception( "Повторное определение поля {$name} у объекта ".get_class($this) );

		$className = 'Field'.$type;
		$fields[$name] =& new $className( $name, $caption, $properties );
		$fields[$name]->namePrefix = get_class($this);
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
			$this->loadByQuery( "select * from $this->dbTable where id = $id" );
		}
	}

	public function &getField( $fieldName )
	{
		return $this->fields[$fieldName];
	}

	public function getHTML()
    {
        return '';
    }
}

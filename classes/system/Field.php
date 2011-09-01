<?php
/**
 * Created by JetBrains PhpStorm.
 * User: r.khuramshin
 * Date: 29.08.11
 * Time: 11:26
 * To change this template use File | Settings | File Templates.
 */

define( 'FLD_STRING',   'String'   );
define( 'FLD_TEXT',     'Text'     );
define( 'FLD_INT',      'Int'      );
define( 'FLD_FLOAT',    'Float'    );
define( 'FLD_CURRENCY', 'Currency' );
define( 'FLD_DATE',     'Date'     );
define( 'FLD_DATETIME', 'DateTime' );
define( 'FLD_ARRAY',    'Array'    );
define( 'FLD_TABLE',    'Table'    );
define( 'FLD_OBJECT',   'Object'   );

class Field
{
	protected $name = '';
	protected $caption = '';
	protected $value = null;

	public $defaultValue   = null;
	public $readOnly       = true;
	public $inputType      = 'text';
	public $htmlAttributes = '';

	public function __construct( $name, $caption, &$properties=array() )
	{
		$this->name = $name;
		$this->caption = $caption;
		foreach( $properties as $propName => $propVal )
		{
			$this->$propName =& $propVal;
			if( $propName == 'defaultValue' )
			{
				$this->setValue( $propVal );
			}
		}
	}

	public function resetToDefault()
	{
		$this->value = $this->defaultValue;
	}

	public function setValue( $value )
	{
		$this->value = $value;
	}

	public function getValue()
	{
		return $this->value;
	}

	public function toHTML()
	{
		$readonly = $this->readOnly ? 'readonly' : '' ;
		$name = isset($this->namePrefix) ? $this->namePrefix.'_'.$this->name : $this->name ;
		return "<input id='$name' name='$name' type='$this->inputType' value='$this->value' $readonly $this->htmlAttributes />";
	}

	public function toJSON(){ return ''; }
	public function toXML(){ return ''; }
	
	public function toString()
	{
		return (string)$this->getValue();
	}

	public function __toString()
    {
        return toString();
    }

	public function getCaption()
	{
		return $this->caption;
	}
}

?>
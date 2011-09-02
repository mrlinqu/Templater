<?php

class User extends Module
{
    public function __construct()
    {
	    $this->dbTable = DB_PREFIX."users";

	    $this->newField( FLD_STRING, "login",  "�����",              array("readOnly"=>false) );
	    $this->newField( FLD_STRING, "passwd", "������",             array("readOnly"=>false, "inputType"=>"password") );
	    $this->newField( FLD_ARRAY,  "groups", "�������� � �������", array("defaultValue"=>array(3)) );
	    $this->newField( FLD_STRING, "name",   "���",                array("defaultValue"=>"������") );
	    $this->newField( FLD_STRING, "email",  "Email" );
	    
	    parent::__construct();

	    if( $this->login && $this->passwd ) // � POST'� ������ ������ ��� ��������������
	    {
		    $this->auth();
	    }
	    elseif( isset($_SESSION['User_id']) && intval($_SESSION['User_id']) > 0 )
	    {
		    $this->loadById( $_SESSION['User_id'] );
	    }
	    
    }

	protected function auth()
	{
		$pass = MD5($this->login.$this->passwd);
		$this->loadByQuery( "select * from {$this->dbTable} where login = '{$this->login}' and passwd = '{$pass}'" );
		if( !$this->id )
		{
			$this->msg->addMessage( '�������� ����� ��� ������' );
		}
	}

	public function loginPanel()
	{
		if( $this->id )
		{
			return Templater::render( 'userPanel.html' );
		} else {
			$vars = array(
				'loginField'  => $this->getField('login'),
				'passwdField' => $this->getField('passwd')
			);
			return Templater::render( 'loginPanel.html', $vars );
		}
	}

}

?>
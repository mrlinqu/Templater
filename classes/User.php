<?php

class User extends Module
{
	protected $dbTable = 'users';

    public function __construct()
    {
	    $this->newField( FLD_STRING, "login",  "�����", array("readOnly"=>false) );
	    $this->newField( FLD_STRING, "passwd", "������", array("readOnly"=>false) );
	    $this->newField( FLD_ARRAY,  "groups", "�������� � �������", array("defaultValue"=>array(3)) );
	    $this->newField( FLD_STRING, "name",   "���", array("defaultValue"=>"������") );
	    
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
		$this->loadByQuery( "select * from {$this->dbTable} where login = '{$this->login}' and passwd = '{$this->passwd}'" );
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
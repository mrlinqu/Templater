<?php

class User
{
    protected $db = &Singleton::getInstance( 'Database' );
    public $user = array();
    
    public function __construct()
    {
        if( isset( $_SESSION['user_id'] ) AND  intval( $_SESSION['user_id'] ) > 0 )
        {
            $this->user = $this->db->onerow_query( 'SELECT * FROM ' . DB_PREFIX . '_users WHERE user_id=\'' . intval( $_SESSION['user_id'] ) . '\'' );
            if( $this->user['id'] )
            {
                $this->user['groups'] = explode(',', $this->user['groups']);
            }
        } elseif( isset( $_POST['login'] ) and $_POST['login'] == "submit" ) {
            $_POST['login_name'] = $db->safesql( $_POST['login_name'] );
            $_POST['login_password'] = @md5( $_POST['login_password'] );
        }
    }
    public function getHTML()
    {
        
    }
}

?>
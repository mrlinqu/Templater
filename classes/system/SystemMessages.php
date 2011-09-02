<?php
/**
 * Created by JetBrains PhpStorm.
 * User: r.khuramshin
 * Date: 01.09.11
 * Time: 12:47
 */
 
class SystemMessages
{
	const delayedRender = true;
	
	protected $messages = array();
	
	public function addMessage( $text )
	{
		$this->messages[] = $text;
	}

	public function getHTML()
    {
        return '<div>'.implode( '</div><div>', $this->messages ).'</div>';
    }
}

?>
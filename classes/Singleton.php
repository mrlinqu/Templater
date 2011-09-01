<?php
/**
 * Created by JetBrains PhpStorm.
 * User: roman
 * Date: 27.08.11
 * Time: 12:57
 * To change this template use File | Settings | File Templates.
 */
 
class Singleton {

    protected static $instance = array();  // object instance

    public static function getInstance( $className )
    {
        if ( is_null(self::$instance[$className]) )
        {
            self::$instance[$className] =& new $className;
        }
        return self::$instance[$className];
    }

}

?>
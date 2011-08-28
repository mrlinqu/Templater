<?php
/**
 * Created by JetBrains PhpStorm.
 * User: roman
 * Date: 27.08.11
 * Time: 23:23
 * To change this template use File | Settings | File Templates.
 */
 
class SimpleClass
{
    private $var = 'not init';
    public function getHTML()
    {
        $this->var = "init by getHTML()";
        return 'SimpleClass was here';
    }
    public function simpleMethod()
    {
        return $this->var;
    }
}

?>
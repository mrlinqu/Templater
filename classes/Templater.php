<?php
/**
 * Created by JetBrains PhpStorm.
 * User: roman
 * Date: 27.08.11
 * Time: 13:01
 */
 
class Templater
{
    public static $templates_dir  = 'templates/';
    public static $default_classmethod = 'getHTML';

    public static function render( $templateFile, $variables=array() )
    {
        $template = file_get_contents( self::$templates_dir . $templateFile );

        /*****************************
         *  ������������
         */
        $ext_matches = array();
        $blocks_matches = array();
        while( preg_match( '/{%\s*extends\s+(\S+)\s*%}/is', $template, $ext_matches ) )
        {
            if( preg_match_all( '/{%\s*block\s+(\S+)\s*%}(.*){%\s*\/block\s+\1\s*%}/is', $template, $blocks_matches ) )
            // [0]-���� � ������, [1]-�������� ����� [2]-���������� �����
            {
                $template = file_get_contents( self::$templates_dir . $ext_matches[1] );
                foreach( $blocks_matches[1] as $key => $blockname )
                {
                    $template = preg_replace( '/{%\s*block\s+' . $blockname . '\s*%}.*{%\s*\/block\s+' . $blockname . '\s*%}/is', $blocks_matches[0][$key], $template );
                }
            }
        }
        $template = preg_replace( '/{%\s*block\s+\S+\s*%}|{%\s*\/block\s+\S+\s*%}/is', '', $template );

	    /*****************************
         *  ������
         */
        $class_matches = array();
	    $antiDouble = array();
        if( preg_match_all( '/{:\s*(\w+)(\s*\|\s*(\w+))?\s*:}/is', $template, $class_matches ) )
        // [0]-���� � ������, [1]-�������� ������ [2]-not use [3]-�������� ������
        {
	        $delayedClasses = array();
            foreach( $class_matches[1] as $key => $classname )
            {
	            if( isset($antiDouble[ $classname.'::'.$class_matches[3][$key] ]) ) //������, �� ��� ������� ������� � ���������� - ���� ��...
		            continue;

	            $antiDouble[ $classname.'::'.$class_matches[3][$key] ] = true;

	            if( $classname::delayedRender ) // ��������� ������� �� �����, ����� ���� ��������� �������
	            {
		            $delayedClasses[$classname] = $class_matches[3][$key];
		            continue;
	            }
	            $template = self::renderClass( $classname, $class_matches[3][$key], $template );
            }
	        foreach( $delayedClasses as $classname => $method ) // ��������� ���������� ������
	        {
		        $template = self::renderClass( $classname, $method, $template );
	        }
        }

	    /*****************************
         *  ����������
         */
        $var_matches = array();
        if( preg_match_all( '/{{\s*(\w+)(\s*\|\s*(\w+)(\s*\(\s*(.+)\s*\))?)?\s*}}/is', $template, $var_matches ) )
        // [0]-���� � ������, [1]-�������� ���������� [2]-not use [3]-�������� ������������ [4]-not use [5]-��������� ������������
        {
            foreach( $var_matches[1] as $key => $varname )
            {
	            if( isset($antiDouble[ $varname.'|'.$var_matches[3][$key].'('.$var_matches[5][$key].')' ]) ) //������, �� ��� ������� ������� � ���������� - ���� ��...
		            continue;

	            $antiDouble[ $varname.'|'.$var_matches[3][$key].'('.$var_matches[5][$key].')' ] = true;

                if( $var = $variables[$varname] )
                {
                    if( $modifier = $var_matches[3][$key] ) //���� �����������
                    {
                        if( $modparams = $var_matches[5][$key] ) //����������� � �����������
                        {
                            $template = preg_replace( '/{{\s*'.$varname.'\s*\|\s*'.$modifier.'\s*\(\s*'.$modparams.'\s*\)\s*}}/is', self::$modifier( $var, $modparams ), $template );
                        }else{
                            $template = preg_replace( '/{{\s*'.$varname.'\s*\|\s*'.$modifier.'\s*}}/is', self::$modifier( $var ), $template );
                        }
                    }else{
                        $template = preg_replace( '/{{\s*' . $varname . '\s*}}/is', ($var instanceof Field) ? $var->toHTML() : $var, $template );
                    }
                }else{

                }
            }
        }

        /*****************************
         *  end
         */
        return $template;
    }


	public static function renderClass( $classname, $method, $template )
	{
		$class = Singleton::getInstance( $classname );
		if( $method )
		{
			return preg_replace( '/{:\s*' . $classname . '\s*\|\s*' . $method . '\s*:}/is', $class->$method(), $template );
		}else{
			$method = self::$default_classmethod;
			return preg_replace( '/{:\s*' . $classname . '\s*:}/is', $class->$method(), $template );
		}
	}

	/**********************************************
	 * ������������ ����������
	 **********************************************/
    public static function date( $var, $format ) {
        if( $var instanceof Field )
            return date_format( $var->toString(), $format );
        else
            return date_format( $var, $format );
    }
	
    public static function uppercase( $var ) {
        if( $var instanceof Field )
            return strtoupper( $var->toString() );
        else
            return strtoupper( $var );
    }

    public static function lowercase( $var ) {
        if( $var instanceof Field )
            return strtolower( $var->toString() );
        else
            return strtolower( $var );
    }

	public static function caption( $var ) {
		if( $var instanceof Field )
			return $var->getCaption();
		else
			return '';
    }
}

?>
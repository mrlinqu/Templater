<?php
/**
 * Created by JetBrains PhpStorm.
 * User: roman
 * Date: 27.08.11
 * Time: 13:01
 * To change this template use File | Settings | File Templates.
 */
 
class Templater
{
    public $templates_dir  = 'templates/';
    public $default_classmethod = 'getHTML';

    public function parse( $templateFile, $variables, $extends=true )
    {
        $template = file_get_contents( $this->templates_dir . $templateFile );

        // ������������ ->
        $ext_matches = array();
        $blocks_matches = array();
        while( preg_match( '/{%\s*extends\s+(\S+)\s*%}/is', $template, $ext_matches ) )
        {
            if( preg_match_all( '/{%\s*block\s+(\S+)\s*%}(.*){%\s*\/block\s+\1\s*%}/is', $template, $blocks_matches ) )
            // [0]-���� � ������, [1]-�������� ����� [2]-���������� �����
            {
                $template = file_get_contents( $this->templates_dir . $ext_matches[1] );
                foreach( $blocks_matches[1] as $key => $blockname )
                {
                    $template = preg_replace( '/{%\s*block\s+' . $blockname . '\s*%}.*{%\s*\/block\s+' . $blockname . '\s*%}/is', $blocks_matches[0][$key], $template );
                }
            }
        }
        $template = preg_replace( '/{%\s*block\s+\S+\s*%}|{%\s*\/block\s+\S+\s*%}/is', '', $template );
        // <- ������������

        // ������ ->
        // TODO ���������� ����� preg_match: ���� � ��� �� �����::����� ����� ���� � ������� ��������� ���, � ��������� ������ ������ ��������
        $class_matches = array();
        if( preg_match_all( '/{:\s*(\w+)(\s*\|\s*(\w+))?\s*:}/is', $template, $class_matches ) )
        // [[0]-���� � ������, [1]-�������� ������ [2]-not use [3]-�������� ������
        {
            foreach( $class_matches[1] as $key => $classname )
            {
                $class = Singleton::getInstance( $classname );
                if( $class_matches[3][$key] )
                {
                    $method = $class_matches[3][$key];
                    $template = preg_replace( '/{:\s*' . $classname . '\s*\|\s*' . $method . '\s*:}/is', $class->$method(), $template );
                }else{
                    $method = $this->default_classmethod;
                    $template = preg_replace( '/{:\s*' . $classname . '\s*:}/is', $class->$method(), $template );
                }
            }
        }
        // <- ������

        // ���������� ->
        $var_matches = array();
        if( preg_match_all( '/{{\s*(\w+)(\s*\|\s*(\w+)(\s*\(\s*(.+)\s*\))?)?\s*}}/is', $template, $var_matches ) )
        // [[0]-���� � ������, [1]-�������� ���������� [2]-not use [3]-�������� ������������ [4]-not use [5]-��������� ������������
        {
            foreach( $var_matches[1] as $key => $varname )
            {
                if( $var = $variables[$varname] )
                {
                    if( $modifier = $var_matches[3][$key] ) //���� �����������
                    {
                        if( $modparams = $var_matches[5][$key] )
                        {
                            $template = preg_replace( '/{{\s*'.$varname.'\s*\|\s*'.$modifier.'\s*\(\s*'.$modparams.'\s*\)\s*}}/is', $this->$modifier( $var, $modparams ), $template );
                        }else{
                            $template = preg_replace( '/{{\s*'.$varname.'\s*\|\s*'.$modifier.'\s*}}/is', $this->$modifier( $var ), $template );
                        }
                    }else{
                        $template = preg_replace( '/{{\s*' . $varname . '\s*}}/is', $var, $template );
                    }
                }else{

                }
            }
        }
        // <- ����������
        return $template;
    }

    private function date( $var, $format )
    {
        return date_format( $var, $format );
    }
    private function uppercase( $var )
    {
        return strtoupper( $var );
    }
    private function lowercase( $var )
    {
        return strtolower( $var );
    }
}

?>
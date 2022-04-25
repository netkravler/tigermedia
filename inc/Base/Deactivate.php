<?php

/**
 * 
 * @package gestus_nord
 */

namespace Inc\Base;

class Deactivate
{


    function __construct()
    {
        
        register_activation_hook( __FILE__ , array ( $this  , 'deactivate'));
        
    }
    


    public static function deactivate(){

        flush_rewrite_rules(  );
    }
}

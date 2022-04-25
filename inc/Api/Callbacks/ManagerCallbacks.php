<?php

namespace Inc\Api\Callbacks  ;

use Inc\Base\BaseController;

class ManagerCallbacks extends BaseController
{

   public function checkboxSanitize( $input  )
   {


        $output = array();



        foreach($this->managers['managers']  as $key => $value)
        { 
            
            isset( $input[$key] ) ? $output[$key] = true : null ;
        }

        return $output;

   }




   public function checkboxField($args)
   {



    $name = $args["label_for"];
    $class = $args["class"];
    $option_name = $args['option_name'];
    $checkbox = isset(get_option( $option_name )[$name])  ? 'checked' : '' ;

    echo '<div class="'.$class.'"><input type="checkbox" class="'.$class.'" id="'.$name.'" name="' . $option_name .'['. $name .']" value=true  ' . $checkbox  .'><label for="'.$name.'"><div></div></label></div>';

   }

} 

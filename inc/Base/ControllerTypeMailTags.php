<?php

namespace Inc\Base;

use Inc\Base\BaseController;

class ControllerTypeMailTags extends BaseController
{

    

    public function register()
    {

        

          

    }

   /*public function mail_tag_sanitizer( $args )
    {




        $option = $args['option_name'];
        $parent = $args['parent_name'];
        $posttype = $args['post_type'];
        $arr = $this->get_arr_keys($args['scaffold']);
      
        if ( ! get_option($option)){

          update_option( $option, array());

        }

            $output = get_option($option);
        

        if ( empty($output) ) {

            $options[$posttype] = $arr;

            update_option($option, $options);

        }
               
        else{

        foreach($output[$parent] as $key => $value){
                
        /*array from function
        echo '$arr = ';
        print_r($arr);

        echo '<br/>';
            
        echo '$output[$parent] = ';
        print_r($output[$parent]);

        echo '<br/>';

        echo '$key = ';
        print_r($key);

        echo '<br/>';
        
        echo '$args["post_type"] = ';
        print_r($args['post_type']);

        echo '<br/>';

        echo '$value = ';

        print_r($value);

            

        if(!in_array($output, $posttype) && $posttype === $key){

            $output["$posttype"] = $arr;

        }else{

            $output["$posttype"] = $this->get_arr_keys($value);

        }


       }
       update_option($option, $output);
    }

       


            
    }
*/


    public function mail_tag_sanitizer( $args ) {

        /* map[string,mixed]  $array, string  $key,  string  $value*/

        $option = $args['option_name'];
        $parent = $args['parent_name'];
        $key = $args['post_type'];
        $value = $this->get_arr_keys($args['scaffold']);
      
        if ( ! get_option($option)){

          update_option( $option, array());

        }

            $array = get_option($option);


        if (!isset($array[$key]))
            $array[$key] = $value;
        else if (is_array($array[$key]))
            $array[$key][] = $value;
        else
            $array[$key] = array($array[$key], $value);
    
        //return $array;

        update_option($option, $array);
    }

    //return only array keys

    function get_arr_keys($args)
    {

        $scaffold = array();

        foreach($args as $key => $value)
        {
    
            array_push($scaffold, $key);
    
        }

        return $scaffold;

    }


}

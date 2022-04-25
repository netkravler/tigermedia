<?php

namespace Inc\Api\Callbacks ;

class Settings_Callback
{
    public $postID;

    public function settingsSectionManager()
    {

        echo ' Styr alle Taxonomies';
    }
 
    public function settingsSanitize( $input )
    {

        $output = get_option('gestus_settings_manager');  

            
            

            if( isset( $_POST['gestus_settings_manager[page_id]']) && empty( $_POST['gestus_settings_manager[page_id]']) ){
                
                $publish = $_POST['gestus_settings_manager[is_active]'] = 'true' ? 'publish' : 'draft';

                    $args = array(
                    'post_title' => $_POST['gestus_settings_manager[activity]'],
                    'post_content' => '',
                    'post_author' => 1,
                    'post_status' => $publish, 
                    'post_type' => 'activities'
                    
                    );

                $this->postID = wp_insert_post($args);
            }
           
        

    
        

 
        if(isset($_POST['remove']))
        {

            if ( !FALSE === get_post_status( $_POST['page_id'] ) ) {
            //delete the page assositated to the activity
            wp_delete_post($_POST['page_id']);
            }
            //delete activity
            unset($output[$_POST['remove']]);

            return $output;
        }



            if ( count($output) == 0 ) {

                $input['page_id'] = $this->postID;
                $output[$input['activity']] = $input;
                
                
                
                return $output;
            }
    
            foreach ($output as $key => $value) {
                if ($input['activity'] === $key) {
                    $output[$key] = $input;
                   

                } else {
                    $input['page_id'] = $this->postID;
                    $output[$input['activity']] = $input;
                   
                }

                
            }


            return $output;
            
    }


    public function textField( $args )
    {

        //var_dump(isset($_POST['edit_activity']));

        $name = $args["label_for"];
        $class = $args["class"];
        $option_name = $args['option_name'];

        $type = $args["field_type"];

        $required = $args["required"];

        $value = '';

        if( isset($_POST['edit_activity']))
        {
            $input = get_option( $option_name );

            $value = isset($input[$_POST['edit_activity']][$name]) ? $input[$_POST['edit_activity']][$name] : '';

        }

        $placholder = isset($args['placeholder']) ? $args['placeholder'] : null;
   
      
        
        echo '<div ><input type="' . $type . '" class="'. $class .'" value="'. $value .'" id="'.$name.'" name="' . $option_name .'['. $name .']"    placeholder="'.$placholder.'"  '.$required.'><label for="'.$name.'" ><div></div></label></div>';
   

    }

     public function checkboxField($args)
     {
 
 
        $required = $args["required"];
        $name = $args["label_for"];
        $class = $args["class"];
        $option_name = $args['option_name'];

        $checked = false;

        if( isset($_POST['edit_activity']))
        {

        $checkbox = get_option( $option_name );

        $checked = isset($checkbox[$_POST['edit_activity']][$name])  ?: false ;

        }
    
        echo '<div class="'.$class.'"><input type="checkbox" class="'.$class.'" id="'.$name.'" name="' . $option_name .'['. $name .']" value=true  ' . ( $checked ? 'checked' : '')  .' '.$required.'><label for="'.$name.'"><div></div></label></div>';
    
     }

     
}

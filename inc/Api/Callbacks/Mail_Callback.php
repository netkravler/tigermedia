<?php

namespace Inc\Api\Callbacks ;


use Inc\Tools\Tool_box;

class Mail_Callback 
{
    
   

    public function mailSectionManager()
    {

        echo ' Styr alle email';
    }
 
    public function mailSanitize( $input )
    {

    
            $output = get_option('gestus_email_manager');       
    
            if(isset($_POST['remove']))
            {
                unset($output[$_POST['remove']]);
    
                return $output;
            }
    
            if ( count($output) == 0 ) {
                $output[$input['email_title']] = $input;
    
                return $output;
            }
    
            foreach ($output as $key => $value) {
                if ($input['email_title'] === $key) {
                    $output[$key] = $input;
                } else {
                    $output[$input['email_title']] = $input;
                }
    
                
            }
            return $output;
    
    
    
                
        }
    
    
        public function textField(  $args )
        {
    
            global $_POST;
          
            $option_name = $args['option_name'];
            $name = $args["label_for"];
            $class = $args["class"];

    
            $type = $args["field_type"];
    
            $autocomp = $args["autocomplete"];
           
            
    
            $value = isset($args['value']) ? $args['value'] : '';
    
            if( isset($_POST['edit_field']))
            {
                $input = get_option( $option_name );

                
    
                $value = isset($input[$_POST['edit_field']][$name]) ? $input[$_POST['edit_field']][$name] : '';
    
            }
    
            $placholder = isset($args['placeholder']) ? $args['placeholder'] : null;


       
          switch($type){


            

            case 'text':

                echo '<div ><input type="text" class="'. $class .'" value="'. $value .'" id="'.$name.'" name="' . $option_name .'['. $name .']"    placeholder="'.$placholder.'" autocomplete="'.$autocomp.'" required><label for="'.$name.'" ><div></div></label></div>';
       
            break;

            case 'hidden':

                echo '<div ><input type="hidden" class="'. $class .'" value="'. $value .'" id="'.$name.'" name="' . $option_name .'['. $name .']"    placeholder="'.$placholder.'" autocomplete="'.$autocomp.'" required><label for="'.$name.'" ><div></div></label></div>';
       
            break;

            case 'number':

                echo '<div ><input type="number" class="'. $class .'" value="'. $value .'" id="'.$name.'" name="' . $option_name .'['. $name .']"    placeholder="'.$placholder.'" autocomplete="'.$autocomp.'" required><label for="'.$name.'" ><div></div></label></div>';
       
            break;

            case 'textarea':

            $settings = array(
                'media_buttons' => false,
                'textarea_name' => $option_name.'['.$name.']', 
                'editor_class' => $class
             );

                wp_editor( $value , $option_name.'['.$name.']' ,  $settings );
                //echo '<label for="'.$name.'"><textarea   id="'.$name.'" name="' . $option_name .'['. $name .']"    placeholder="'.$placholder.'" >'. $value .'</textarea></label>';
       
            break;

            case 'default':

                echo '<div ><input type="'. $type .'" class="'. $class .'" value="'. $value .'" id="'.$name.'" name="' . $option_name .'['. $name .']"    placeholder="'.$placholder.'" autocomplete="'.$autocomp.'" required><label for="'.$name.'" ><div></div></label></div>';
       
            break;

          }
       
             
    
        }
    
         public function checkboxField($args)
         {

            $name = $args["label_for"];
            $classes = $args["class"];
            $option_name = $args['option_name'];
    
            $checked = false;
    
            if( isset($_POST['edit_field']))
                {
        
                    $checkbox = get_option( $option_name );
            
                    $checked = isset($checkbox[$_POST['edit_field']][$name])  ?: false ;
            
                }
         
                echo '<div class="'.$classes.'"><input type="checkbox" class="'.$classes.'" id="'.$name.'" name="' . $option_name . '[' . $name . ']" value=true  ' . ( $checked ? 'checked' : '')  .'><label for="'.$name.'"><div></div></label></div>';
    
         }
    
    
         public function checkboxPostTypesField( $args )
         {
             $output = '';
             $name = $args['label_for'];
             $classes = $args['class'];
             $option_name = $args['option_name'];
             $checked = false;
     
             if ( isset($_POST["edit_field"]) ) {
                 $checkbox = get_option( $option_name );
             }
     
             $post_types = get_post_types( array( 'show_ui' => true, 'public'   => true ) );
     
             foreach ($post_types as $post) {
     
                 if ( isset($_POST["edit_field"]) ) {
                     $checked = isset($checkbox[$_POST["edit_field"]][$name][$post]) ?: false;
                 }
     
                 $output .= '<div class="' . $classes . ' mb-10"><input type="checkbox" id="' . $post . '" name="' . $option_name . '[' . $name . '][' . $post . ']" value="1" class="" ' . ( $checked ? 'checked' : '') . '><label for="' . $post . '"><div></div></label> <strong>' . $post . '</strong></div>';
             }
     
   
             echo $output;
         }
    
         public function optionsOutput( $args )
         {
             $output = '';
             $name = $args['label_for'];

             $option_name = $args['option_name'];

     
             if ( isset($_POST["edit_field"]) ) {

                $input = get_option( $option_name );

                $fieldvalue = isset($input[$_POST['edit_field']][$name]) ? $input[$_POST['edit_field']][$name] : '';
             
            }
     
            
             $types_arr = array();

             $types = get_option('use_mails_on_posttype');

             $usedtypes = get_option('gestus_email_manager');



             foreach ($usedtypes as $type => $value){

                array_push($types_arr, $value['posttype']);
                
             }

             

             if ( isset($_POST["edit_field"]) ) {

             $output .= $fieldvalue;

             $output .= '<input type="hidden"  value="'. $fieldvalue .'" id="'.$name.'" name="' . $option_name . '[' . $name . ']"    ><label for="'.$name.'" ></label>';
     
             }else{

             $output .= '<select id="'.$name.'" name="' . $option_name . '[' . $name . ']" required>';

             $output .= '<option value="" selected> Vælg post type </option>';
                foreach ($types['']['email_chosen_posttype'] as $post => $value) 
                {
                    if (!in_array($post, $types_arr)) {
                    $output .= '<option value="'. $post .'" >'. $post .'</option>';
                    }
                }
             }    
             echo $output;
         }
    
    
    
    
    
    }
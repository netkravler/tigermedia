<?php


namespace Inc\Api\Callbacks ;


class PageSettingsCallback
{
 
    public $postID;

    public function taxSectionManager()
    {

        echo ' Styr alle Taxonomies';
    }
 
    public function taxSanitize( $input )
    {

        $output = get_option('gestus_page_manager');       


       
        if(isset($_POST['remove']))
        {
            unset($output[$_POST['remove']]);

            return $output;
        }


            if ( count($output) == 0 ) {
                $output[$input['page_settings']] = $input;
    
                return $output;
            }
    
            foreach ($output as $key => $value) {
                if ($input['page_settings'] === $key) {
                    $output[$key] = $input;
                } else {
                    $output[$input['page_settings']] = $input;
                }

                
            }
            return $output;
            
    }


    public function textField( $args )
    {

        $output = get_option('gestus_page_manager');       

        $name = $args["label_for"];
        $class = $args["class"];
        $option_name = $args['option_name'];

        $type = $args["field_type"];

        $editabble = '';

        $value = isset($output[$name]) ? $output[$name] : '';

        $placholder = isset($args['placeholder']) ? $args['placeholder'] : null;
   
      
   
       echo '<div ><input type="' . $type . '" class="'. $class .'" value="'. $value .'" id="'.$name.'" name="' . $option_name .'['. $name .']"    placeholder="'.$placholder.'" '. $editabble .'><label for="'.$name.'" ><div></div></label></div>';
   

    }

     public function checkboxField($args)
     {
 
 
 
        $name = $args["label_for"];
        $class = $args["class"];
        $option_name = $args['option_name'];

        $checked = false;

        if( isset($_POST['edit_page_settings']))
        {

        $checkbox = get_option( $option_name );

        $checked = isset($checkbox[$_POST['edit_page_settings']][$name])  ?: false ;

        }
    
        echo '<div class="'.$class.'"><input type="checkbox" class="'.$class.'" id="'.$name.'" name="' . $option_name .'['. $name .']" value=true  ' . ( $checked ? 'checked' : '')  .'><label for="'.$name.'"><div></div></label></div>';
    
     }

     public function checkboxPostTypesField( $args )
     {
         $output = '';
         $name = $args['label_for'];
         $classes = $args['class'];
         $option_name = $args['option_name'];
         $checked = false;
 
         if ( isset($_POST["edit_page_settings"]) ) {
             $checkbox = get_option( $option_name );
         }
 
         $post_types = get_post_types( array( 'show_ui' => true ) );
 
         foreach ($post_types as $post) {
 
             if ( isset($_POST["edit_page_settings"]) ) {
                 $checked = isset($checkbox[$_POST["edit_page_settings"]][$name][$post]) ?: false;
             }
 
             $output .= '<div class="' . $classes . ' mb-10"><input type="checkbox" id="' . $post . '" name="' . $option_name . '[' . $name . '][' . $post . ']" value="1" class="" ' . ( $checked ? 'checked' : '') . '><label for="' . $post . '"><div></div></label> <strong>' . $post . '</strong></div>';
         }
 
         // echo '<div class="' . $classes . '"><input type="checkbox" id="' . $name . '" name="' . $option_name . '[' . $name . ']" value="1" class="" ' . ( $checked ? 'checked' : '') . '><label for="' . $name . '"><div></div></label></div>';
 
         echo $output;
     }
}

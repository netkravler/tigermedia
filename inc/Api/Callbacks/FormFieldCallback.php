<?php

namespace Inc\Api\Callbacks ;

use Inc\Base\BaseController;
use Inc\Tools\Tool_box;

use WP_Query;

class FormFieldCallback extends BaseController
{

    



  

    public function Sanitize( $input )
    {

      

        $manager = (isset($_POST['manager']) ? $_POST['manager'] : '');

        $output_key = $this->managers['managers'][$manager]['type'];


        $output = get_option($manager);  

       
	   if ( isset($_POST["remove"]) ) {
		unset($output[$_POST["remove"]]);

		return $output;
        }
    



        if ( count($output) == 0 ) {

            

            $output[$input["$output_key"]] = $input;
        
            return $output;
        }

        foreach ($output as $key => $value) {
           
          

            if ($input["$output_key"] === $key) {
                $output[$key] = $input;
            } else {
                $output[$input["$output_key"]] = $input;
            }
        }

	
            return $output;
            
    }

    public function textField(  $args )
    {

       
        global $_POST;
      
        $toolbox = new Tool_box();
        $id = $this->RandomString(10);
      

        $option_name = $args['option_name'];
        $name = $args["label_for"];
        $class = $args["class"];

        $readonly = isset($args["readonly"]) && isset($_POST['edit_field']) && $_POST['edit_field'] ? 'readonly' : '';
        
        $type = $args["field_type"];

        $autocomp = 'autocomplete'.$this->RandomString(20);
       
        

        $value = isset($args['value']) ? $args['value'] : '';

        if( isset($_POST['edit_field']))
        {

           // var_dump($_POST['edit_field']);
              //var_dump($args);
            $input = get_option( $option_name );

            //var_dump($input['']);

            $value = isset($input[$_POST['edit_field']][$name]) ? $input[$_POST['edit_field']][$name] : '';

        }

        $placholder = isset($args['placeholder']) ? $args['placeholder'] : null;


   
      switch($type){


        case 'file':



            echo '<div ><input type="file" class="'. $class .'" value="'. $value .'" id="'.$name. '" name="'. $name .'"    placeholder="'.$placholder.'" autocomplete="'.$autocomp.'" '. $readonly . '><label for="'.$name.  $id .'" ><div></div></label></div>';
   
        break;
        

        case 'text':

            echo '<div ><input type="text" class="'. $class .'" value="'. $value .'" id="'.$name.  $id .'" name="' . $option_name .'['. $name .']"    placeholder="'.$placholder.'" autocomplete="'.$autocomp.'" '. $readonly . '  ><label for="'.$name.  $id .'" ><div></div></label></div>';
   
        break;

        
        case 'url':

            echo '<div ><input type="url" class="'. $class .'" value="'. $value .'" id="'.$name.  $id .'" name="' . $option_name .'['. $name .']"    placeholder="'.$placholder.'" autocomplete="'.$autocomp.'" '. $readonly . '><label for="'.$name.  $id .'" ><div></div></label></div>';
   
        break;

        
        case 'hidden':

            echo '<div ><input type="hidden" class="'. $class .'" value="'. $value .'" id="'.$name.  $id .'" name="' . $option_name .'['. $name .']"    placeholder="'.$placholder.'" autocomplete="'.$autocomp.'" '. $readonly . '><label for="'.$name.  $id .'" ><div></div></label></div>';
   
        break;

        case 'number':

            echo '<div ><input type="number" class="'. $class .'" value="'. $value .'" id="'.$name.  $id .'" name="' . $option_name .'['. $name .']"    placeholder="'.$placholder.'" autocomplete="'.$autocomp.'" '. $readonly . '><label for="'.$name.  $id .'" ><div></div></label></div>';
   
        break;

        case 'textarea':

        $settings = array(
            'media_buttons' => false,
            'textarea_name' => $option_name.'['.$name.']', 
            'editor_class' => $class,
            'placeholder' =>  $placholder
         );

            wp_editor( $value , 'content'.$name ,  $settings );
            //echo '<label for="'.$name.'"><textarea   id="'.$name.'" name="' . $option_name .'['. $name .']"    placeholder="'.$placholder.'" >'. $value .'</textarea></label>';
   
        break;

        case 'default':

            echo '<div ><input type="'. $type .'" class="'. $class .'" value="'. $value .'" id="'.$name.  $id .'" name="' . $option_name .'['. $name .']"    placeholder="'.$placholder.'" autocomplete="'.$autocomp.'" '. $readonly . '><label for="'.$name.  $id .'" ><div></div></label></div>';
   
        break;

      }
   
         

    }

    public function drop_down( $args )
    {


        $id = $this->RandomString(10);

        $posttype = get_option('use_mails_on_posttype');
      
        $accepted_posttypes = $posttype[array_key_first($posttype)]['post_types'];


        $option_name = $args['option_name'];
        $name = $args["label_for"];
        $class = $args["class"];


        
        $option = get_option($option_name);

      /*  var_dump([$option_name]);
       
        var_dump($_POST['edit_field']);

        var_dump($option[$_POST['edit_field']]['individual_types']);*/


       $hidden = !isset($option[$_POST['edit_field']]['individual_types']) ? 'hide': '' ;

       //var_dump($hidden);

        

?>

       <?php if($args['select_type'] =='posttypes')
        {?>

        <select   class="<?php echo $class  ?> "  id="<?php echo $name.  $id ?>" name="<?php echo $option_name .'['. $name .'].' ?>"     >
        <?php echo  '<option >Vælg typen</option>';
  
              foreach($accepted_posttypes as $type => $value){
  

                      echo  '<option value="'. $type .'"> ' . $type . ' </option>';
  
                  }
          
              
        
              echo ' </select>';

            }
            
            if($args['select_type'] =='pages'){


            

              $arg = array(
                'post_type' => $args["post_type"],
                'posts_per_page' => -1
              );
                $query = new WP_Query($arg);
                if ($query->have_posts() ) : 
                    ?>
                    <select   class="<?php echo $class ?>"  id="<?php echo $name.  $id ?>" name="<?php echo $option_name .'['. $name .'].' ?>"     >
                    <option value="">Vælg siden du vil bruge</option>
                    <?php
                    while ( $query->have_posts() ) : $query->the_post();
                            echo '<option value="' . get_the_ID() . '">' . get_the_title() . '</option>';
                    endwhile;
                    echo '</select>';
                    wp_reset_postdata();
                endif;
            }
    }

     public function checkboxField($args)
     {
 
 
 
        $name = $args["label_for"];
        $class = $args["class"];
        $option_name = $args['option_name'];

        $checked = false;

        if( isset($_POST['edit_field']))
        {

        $checkbox = get_option( $option_name );

        $checked = isset($checkbox[$_POST['edit_field']][$name])  ?: false ;

        }
    
        echo '<div class="'.$class.'"><input type="checkbox" class="'.$class.'" id="'.$name.'" name="' . $option_name .'['. $name .']" value=true  ' . ( $checked ? 'checked' : '')  .'><label for="'.$name.'"><div></div></label></div>';
    
     }


     public function checkboxFields( $args )
     {

        $toolbox = new Tool_box();
        $id = $this->RandomString(10);

        $output = '';       
        $name = $args['label_for'];
        $classes = $args['class'];
        $option_name = $args['option_name'];
        $checked = false;

        $loops = get_option($args['loop']);
 
         
        $checkbox = get_option( $option_name );
         

 

 
         foreach ($loops[array_key_first($loops)]['post_types'] as $post => $value) {
 
            

                 $checked = isset($checkbox[$_POST['edit_field']]['post_types'][$post]) ?: false;
             
 
             $output .= '<label for="' . $post . $id .  '"><div class="' . $classes . ' mb-10"><input type="checkbox" id="' . $post . $id . '" name="' . $option_name . '[' . $name . '][' . $post . ']" value="1"  ' . ( $checked ? 'checked' : '') . '><label for="' . $post . $id .  '"><div></div></label> <strong > ' . $post . ' </strong></div></label>';
         }
 
          
         echo $output;
     }

     public function checkboxPostTypesField( $args )
     {
        $output = '';       
        $name = $args['label_for'];
        $classes = $args['class'];
        $option_name = $args['option_name'];
        $checked = false;
 
        
         
        $checkbox = get_option( $option_name );
         
        
 
         $post_types = get_post_types( array( 'show_ui' => true, 'public' => true , '_builtin' => false) );
 
         foreach ($post_types as $post => $value) {
 

                 $checked = isset($checkbox[array_key_first($checkbox)][$name][$post]) ?: false;
             
 
             $output .= '<div class="' . $classes . ' mb-10"><input type="checkbox" id="' . $post . '" name="' . $option_name . '[' . $name . '][' . $post . ']" value="1"  ' . ( $checked ? 'checked' : '') . '><label for="' . $post . '"><div></div></label> <strong>' . $post . '</strong></div>';
         }
 
          
         echo $output;
     }



     




     public function handle_file_upload($args){


        $option_name = $args['option_name'];
        $value = '';
        $name = $args["label_for"];
    
        $filename = "$option_name"."[".$name."]";
    
        if( isset($_POST['edit_template']))
        {
            $input = get_option( $option_name );
    
            $value = isset($input[$_POST['edit_template']][$name]) ? $input[$_POST['edit_template']][$name] : '';
    
        }
    
    
        echo '<input type="file" id="'. $args['label_for'] .'" name="' . $filename .'" value="'. $value .'" ';
    
         if(!empty($_FILES[$filename]["tmp_name"]))
         {
            
           $urls = wp_handle_upload($_FILES[$filename], array('test_form' => FALSE));
           $temp = $urls["url"];
           return $temp;  
         }
    
         return $args;
    
    }
}
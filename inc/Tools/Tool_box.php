<?php

namespace Inc\Tools;

class Tool_box
{
    
    public function fields_arr($posttype, $args)
    {

        var_dump($args);

        die;
        $output = get_option('fields_arr');       


 /*
        if ( count($output) == 0 ) {
            $output['fields_arr'] = $input;

            return $output;
        }

       foreach ($output as $key => $value) {
            if ($input['template'] === $key) {
                $output[$key] = $input;
            } else {
                $output[$input['template']] = $input;
            }

            
        }
        return $output; */



    }

    public function  check_user_role($roles, $user_id = null) {

        global $current_user;

        if(!function_exists('wp_get_current_user')) {
            include(ABSPATH . "wp-includes/pluggable.php"); 
        }

        if ($user_id) $user = get_userdata($user_id);
        else $user = wp_get_current_user();
        if (empty($user)) return false;
        foreach ($user->roles as $role) {
            if (in_array($role, $roles)) {
                return true;
            }
        }
        return false;
    }


    public function RandomString($str_len)
    {
        if($str_len)
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = '';
        for ($i = 0; $i < $str_len; $i++) {
            $randstring .= $characters[rand(0, strlen($characters)-1)];
        }
        return $randstring;
    }
    
    //cut string in dessired lenght according to $length
    public function cut_string($string , $length){

        $string = substr($string,0,$length);

        $string = (strlen($string) > $length) ? $string.'...' : $string ;

        return $string;

    }

    //split string into array over defined delimeter $delim
    public function split_string($string , $delim){

         
        $str_arr = explode ($delim, $string);  

        foreach($str_arr as $value){

            echo '<p style="line-height: 1.2; margin:0;">'.$value . '</p>';

        }
        
    }

    //get age by date
    public function get_age($args){

        if($args){

        //date in mm/dd/yyyy format; or it can be in other formats as well
        $birthDate = $args;

        //explode the date to get month, day and year
        $birthDate = explode("-", $birthDate);

        //get age from date or birthdate
        $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]))) > date("md")
        ? ((date("Y") - $birthDate[0]) - 1)
        : (date("Y") - $birthDate[0]));
        return $age ." år";  

        }


    }

    /**
     * check if value is in a string or an array 
     * 
     * 
     * @return true or false 
     * */



    public function if_in_string($use_string , $find_value)
    {

        if(is_array($use_string)){

         if(in_array($find_value, $use_string)) {return true;}

        }
        else
        {

        if (strpos($use_string, $find_value) !== false) {return true;}

        }



    }

    //used to change status of given post
    public function change_post_status($post_id , $status){
        $current_post = get_post( $post_id, 'ARRAY_A' );
        $current_post['post_status'] = $status;
        wp_update_post($current_post);
    }


    
    public function display_rating_stars( $num_stars ) {



            $stars = '<p class="stars">';
            for ( $i = 1; $i <= $num_stars; $i ++ ) {
                $stars .= '<span class="dashicons dashicons-star-filled" style="color:orange;"></span>';
            }
            $stars        .= '</p>';
            
            return $stars;

        }

    public function image_modal($modal_title, $button_text)
    {
?>
        <div id="uploadimageModal" class="modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title  text-center"><?php echo $modal_title ?></h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <div id="image_demo" style="width:100%; margin-top:30px"></div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                    <button  class="btn btn-success crop_image "><?php echo $button_text ?></button><button type="button" class="btn btn-default" data-dismiss="modal">Annuller</button>
                    </div>
                </div>
            </div>
        </div>
<?php
    }  
    
    
    public function reformat_time($args){

        return date_i18n("D d M H:i:s", strtotime($args));


    }


    public function rename_file($old, $newpath , $new){


        if (!file_exists( get_home_path() . "wp-content/uploads/".$newpath )) {
            mkdir( get_home_path() . "wp-content/uploads/". $newpath , 0777, true); // create folder if it does not exist
        }

        //find file extension from old file
        $file_ext =  substr($old, strripos($old, '.'));


        //sanitize new filename
        $new =  $this->wpartisan_sanitize_file_name( $new );

        //create path for new file
        $path =  get_home_path() . 'wp-content/uploads/'.$newpath;

        var_dump(content_url() . '/uploads/'. $newpath . '/'.$new . $file_ext);

        //if file allready exist delete it
        if(file_exists(get_home_path() . 'wp-content/uploads/'. $newpath . '/'.$new . $file_ext)){
            unlink(get_home_path() . 'wp-content/uploads/'. $newpath . '/'.$new . $file_ext);
        }

        rename($old , $path .'/' . $new . $file_ext);


        
        return content_url() .'/uploads/'. $newpath . '/'.$new . $file_ext;


    }




    public function wpartisan_sanitize_file_name( $filename ) {

        $sanitized_filename = remove_accents( $filename ); // Convert to ASCII

        $sanitized_filename = strtolower( $sanitized_filename ); // Lowercase
        
        // Standard replacements
        $invalid = array(
            'æ' => 'ae',
            'ø' => 'oe',
            'å' => 'aa',
            ' '   => '-',
            '%20' => '-',
            '_'   => '-',
        );
        $sanitized_filename = str_replace( array_keys( $invalid ), array_values( $invalid ), $sanitized_filename );
    
        $sanitized_filename = preg_replace('/[^A-Za-z0-9-\. ]/', '', $sanitized_filename); // Remove all non-alphanumeric except .
        $sanitized_filename = preg_replace('/\.(?=.*\.)/', '', $sanitized_filename); // Remove all but last .
        $sanitized_filename = preg_replace('/-+/', '-', $sanitized_filename); // Replace any more than one - in a row
        $sanitized_filename = str_replace('-.', '.', $sanitized_filename); // Remove last - if at the end
        
    
        return $sanitized_filename;
    }
    
    public function find_val($array, $key, $val) {
        foreach ($array as $item)
            if (isset($item[$key]) && $item[$key] == $val)
                return true;
        return false;
    }

    public function stamp_me($args)
    {

        return " name='$args' id='$args' ";
    }

    public function ArrayKeys_To_Arr($opt_key /*option name*/ , $opt_tag /* first level array name*/, $opt_posttype /*post types arrays */)
    {
        //option with empty array is created else where
        $output = get_option($opt_key);

        //if option is empty create fill it
        if ( count($output) == 0 ) {
            
            $data = array($opt_tag => array($opt_posttype));
            // update option
            update_option($opt_key, $data);
        }


        $result = [];

        foreach($opt_posttype as $key => $value){

                // if the array exisists in db loop through the values for changes
                if(array_key_exists($key , $output[$opt_tag]))

                    $result[$opt_tag][$key] = $this->UpdateArrayKeys($output[$opt_tag][$key], $value);
                // if it does not exisist create it with the value of the one in the class
                else
                $result[$opt_tag][$key] = $value;
        }

        $combined = $output[$opt_tag];
        $finished = array_push($combined , $result);
        //update the option
        //update the option
        update_option($opt_key, $finished);
    }


    //used to loop through array and create keys if it does not exist, and add them as is if they do
    public function UpdateArrayKeys($arrs, $val)
    
    {


        $result =[];

            foreach($val as $key => $value )
            {
                    //arraykey exsists in class and key in db is an array
                    if(array_key_exists($key , $val) && is_array($arrs)){

                    // if array_key exsists in db array
                    if( array_key_exists($key, $arrs) )
                    // maintain the value from the array in DB
                    $result[$key] = $arrs[$key];

                    }
                    else
                    {
                    //create new key value pair in db from array in class
                    $result[$key] = $val[$key];
                   
                    var_dump($val[$key]);

                    }
            }

            return $result;
    }


}

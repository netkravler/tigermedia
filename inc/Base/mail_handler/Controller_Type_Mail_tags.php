<?php

namespace Inc\Base;

use Inc\Base\BaseController;

class Controller_Type_Mail_tags extends BaseController
{

    public $option_key;

    public $array_tag;

    public $post_type_array;

    public $post_type_array2;

    public function register()
    {
        // key of get_option() in wp db
        $this->option_key = 'public_email_tags';

        //name of surrounding array
        $this->array_tag = 'email_tags';

        //this array can ary from class to class, but allways same structure.
        $this->post_type_array = array(

            //new arrays can be added here
            'partner' => array(
                        /*new keys value pairs kan be added to get_option. 
                        If one is in get_option but not here, delete it from get_option. 
                        else the key value pairs in here should remain as is with no change
                        even if one of the keys get a new value. values to these are handled else where
                        All the pairs here are just examples, the names are NOT exactly as is*/
                        'name' => '',
                        'email' => '', //eg. if this is not present in get_option() add it.
                        'adresse' => '',
                        'postalcode' => '',// if this is not present here but in get_option, delete it from get option.
                        'city' => '',
                        'phonenumber' => '',// eg. if this is an existing key or a new one, ignore the value, but keep the key
                        'gender' => '',
                        'parents' => '',
                        'sibling' => '',
                        'siblings' => ''

                        )

                    );
        
                      //this array can ary from class to class, but allways same structure.
        $this->post_type_array2 = array(

            //new arrays can be added here
            'erhvervspartner' => array(
                        /*new keys value pairs kan be added to get_option. 
                        If one is in get_option but not here, delete it from get_option. 
                        else the key value pairs in here should remain as is with no change
                        even if one of the keys get a new value. values to these are handled else where
                        All the pairs here are just examples, the names are NOT exactly as is*/
                        'name' => '',
                        'email' => '', //eg. if this is not present in get_option() add it.
                        'adresse' => '',
                        'postalcode' => '',// if this is not present here but in get_option, delete it from get option.
                        'city' => '',
                        'phonenumber' => '',// eg. if this is an existing key or a new one, ignore the value, but keep the key
                        'gender' => '',
                        'parents' => '',
                        'sibling' => '',
                        'siblings' => '',
                        'cars' => ''

                        )

                    );
              
        


        //$this->ArrayKeys_To_Arr($this->option_key, $this->array_tag, $this->post_type_array );
        //$this->ArrayKeys_To_Arr($this->option_key, $this->array_tag, $this->post_type_array2 );

    }

    public function ArrayKeys_To_Arr($opt_key /*option name*/ , $opt_tag /* first level array name*/, $opt_posttype /*post types arrays */)
    {
        //option with empty array is created else where
        $output = get_option($opt_key);
       

        //if option is empty fill it
        
        if ( count($output) == 0 ) {
            
            $data = array($opt_tag => $opt_posttype);
            // update option


            update_option($opt_key, $data);

            return $output;
        
}
        
     
        $result = $output;

        foreach($output[$opt_tag] as $key => $value){

            
            
                // if the array exisists in db loop through the values for changes
                if(array_key_exists($key , $output[$opt_tag]) && $key == array_key_first($opt_posttype)){


                    $result[$opt_tag][$key] = $this->UpdateArrayKeys($output[$opt_tag][$key], $opt_posttype[array_key_first($opt_posttype)]);
                }

                // if it does not exisist create it with the value of the one in the class
                else

                    $result[$opt_tag][array_key_first($opt_posttype)] = $opt_posttype[array_key_first($opt_posttype)];
        
                }
    

        update_option($opt_key, $result);
        

        
    }


    //used to loop through array and create keys if it does not exist, and add them as is if they do
    public function UpdateArrayKeys($arrs, $val)
    
    {



        $result =[];

            foreach($val as $key => $value )
            {

                    if(array_key_exists($key , $val)  && is_array($arrs)){



                    if( array_key_exists($key, $arrs) ){
                    // maintain the value from the array in DB

                    $result[$key] = $arrs[$key];
                    }
                    
                    else
                    {

                    //create new key value pair in db from array in class
                    $result[$key] = $val[$key];


                    }
            }
        }
            return $result;
    }


}

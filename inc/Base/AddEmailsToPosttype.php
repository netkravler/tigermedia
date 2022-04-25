<?php

namespace Inc\base;

use Inc\Base\BaseController;

class AddEmailsToPosttype extends BaseController
{

    public function register()
    {

        add_action('add_meta_boxes' , array( $this, 'add_email_metaboxes'));

        add_action('save_post' , array( $this, 'save_emails_meta_box'));
    }

    
    public function add_email_metaboxes(){

        global $post;
    

        $data = get_option( 'gestus_email_manager' );

        foreach($data as $key => $value)
        {

            if($value['post_types'] == get_post_type( $post->ID )){

                add_meta_box(
                    'gestus_email', //id
                    'Kommende mail manager',//title
                    array( $this, 'render_emails_meta_box'),//callback
                    get_post_type( $post->ID ), // screens / post types
                    'side',// context
                    'default'// priority
                        // $args
                                
                );
            }
        }
    
    }


    public function render_emails_meta_box($post)
    {

        $meta = get_post_meta( $post->ID, '_gestus_applicants_key', true );



        $data = get_option( 'gestus_email_manager' );

        foreach($data as $key => $value)
        {

            //var_dump($value);

            if($value['post_types']  && $value['chooseable'] == true  && !$value['sendResponseNow']){

                if( $value['post_id'] == $meta['aid_id'] ){

                        echo'Passer med at post_id '.$value['post_id'].' = ' . $meta['aid_id']. ' og er individuel<br>';  

                }else{

                        echo 'er aktiv og sendes til alle <b>' .$value['email_type'].'</b><br>' ;

                }



            }
            
        }

        
    }


    public function save_emails_meta_box($post)
    {


    }
}

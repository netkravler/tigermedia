<?php


/**
 * 
 * @package gestus_nord
 */

namespace Inc\Base ;

use Inc\Api\SettingsApi;
use Inc\Base\BaseController;


class VolunteerFormController extends BaseController
{
    

    public $data = array();

    public $secund_data = array();
    
    public $mail_tagger;

    public $posttype;
    
    public $contacts = array();

    public $kids = array();

    public $parents = array();

    public $postID;

    public $frivillig_form_shortcode = 'vis_frivillig_form';


    public function register()
    {

        
        if ( !$this->activated('volunteer_manager')) return;

        $this->posttype = 'volunteer';

        $this->mail_tagger = new ControllerTypeMailTags();
        $this->email_scaffold = array();

        $this->settings = new SettingsApi();




        add_shortcode( $this->frivillig_form_shortcode , array($this, 'get_post_page_content') );
   

        add_action('manage_volunteer_type_posts_columns', array( $this , 'sort_order_custom_data'));

        add_action('manage_volunteer_type_posts_custom_column', array( $this , 'sort_order_custom_columns'), 10 , 2);
       



        add_filter('manage_edit_volunteer_type_sortable_columns' , array( $this, 'sortable_order_rows'));

        //$this->settings->addSubPages( $this->subpages )->register();

        add_action('wp_ajax_submit_gestus_volunteer' , array( $this , 'submit_gestus_volunteer'));

        add_action('wp_ajax_nopriv_submit_gestus_volunteer' , array( $this , 'submit_gestus_volunteer'));
     


    }


    public function submit_gestus_volunteer($_post)
    {
      
        $sender = $_POST['name'] .' ' . $_POST['phonenumber'];
        $comments = $_POST['comments'];

        $user_ip = $this->ip;

        $email =  $_POST['email'];
    
    
        $this->data = array(
    
            
            'name' => $_POST['name'],            
            'user_ip' => $user_ip,
            'adresse' => $_POST['adresse'] ,
            'postalcode' => $_POST['postalcode'],
            'city' => $_POST['city'],
            'phonenumber' => $_POST['phonenumber'],
            'email' => $email,
            'gender' =>  $_POST['gender'],
            'attest'=> $_POST['attest'],
            'noattest' => $_POST['noattest'],
            'age' =>  $_POST['age']
    
        );
    
        $this->secund_data = array(

            'approved' => 0,
            'approved_by' => '',
            'approved_email' => '',
            'approved_date' => '',

        );

    
    
    
       
    
        $args = array(
            'post_title' => $sender,
            'post_content' => $comments,
            'post_author' => 1,
            'post_status' => 'publish',
            'post_type' => 'volunteer',
            'meta_input' => array(
                '_gestus_volunteer_key' => array_merge( $this->data , $this->secund_data)
            )
        );
    
        $this->postID = wp_insert_post($args);
    
        $email_args = array(

            'option_name'   => 'email_scaffold',
            'parent_name'   => 'email_tags',
            'post_type'     => $this->posttype,
            'scaffold'      => $this->data
    
        );
    
        
        $this->mail_tagger->mail_tag_sanitizer( $email_args );
       
    
        if ($this->postID) {
            $return = array(
                'status' => 'success',
                'ID' => $this->postID
            );
            wp_send_json($return);
    
            wp_die();
        }
    
        $return = array(
            'status' => 'error'
        );
        wp_send_json($return);
    
        wp_die();
    }




/**
 * 
 * asign template to gestus_aid post type
 * 
 */
public function get_post_page_content() {



    
        ob_start();




        //gestus default form stylesheet
        wp_enqueue_style( 'gestus_style_sheet' );  

      
        //default form stylesheet
        wp_enqueue_style( 'volunteer_form-style' );  
     
        wp_enqueue_style( 'load-fa' ); 
    
        require_once("$this->plugin_path/page-templates/volunteer_type.php");   

        wp_enqueue_script("zip_fetcher");
        
        wp_enqueue_script("validation-script");

     

        return ob_get_clean();


}


    function load_dashicons(){
        wp_enqueue_style('dashicons');
    }


   

   


   
}

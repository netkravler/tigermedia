<?php

/**
 * 
 * @package gestus_nord
 */

namespace Inc\Base;

use Inc\Tools\Tool_box;


use WP_Query;

class BaseController extends Tool_box

{


    
    public $timeformat = 'Y-m-d\TH:i:s';

    public $partnerform_shortcode = 'partner_form';

    public $teen_shortcode = 'teen_form';

    public $erhvervspartnerform_shortcode = 'erhvervspartner_form';
    
    public $now;

    public $ip;

    public $plugin_path;

    public $plugin_url;

    public $plugin;

    public $check_outdated_post;

    public $js_defer = array();

    public $managers = array();

    public $membertypes = array();

    public $page_menues = array();

    public $pickuppoints = array();
    
    //used in TemplateController
    public $page_templates = array();

    public $preventDeletePosts;

    public $post_status = array();

    public function __construct(){

        add_filter( 'esc_url', array( $this, 'defer_parsing_of_js'), 11, 1 );

        $this->now =   strtotime(current_time($this->timeformat));

        $this->plugin_path  = plugin_dir_path ( dirname (__FILE__, 2));
        $this->plugin_url = plugin_dir_url ( dirname (__FILE__, 2));
        $this->plugin = plugin_basename ( dirname ( __FILE__, 3)). '/gestus_nord.php';


        $this->prevent_post_deletion = [ 'gestus_aid' ];

        $this->post_status = array(

            array('post_type' => 'Approved', 'label' => 'Godkendt' ),
            array('post_type' => 'declined', 'label' => 'Afvist' ),
            array('post_type' => 'archived', 'label' => 'Arkiveret' ),



        );

        $this->pickuppoints = array(

                array( 
                'navn' => 'Aalborg Storcenter',
                'adresse' => 'Aalborg Storcenter - Centerkontoret Hobrovej 452, 9200 Aalborg SV'),
                array( 
                'navn' => 'Frederikshavn',
                'adresse' => 'FREDERIK - Tordenskjoldsgade 2, 9900 Frederikshavn'),
                array( 
                'navn' => 'Morshandel',
                'adresse' => 'Morshandel ved Clockhuset – Algade 19, 7900 Nykøbing Mors'),
        );



        $this->page_menues = array(


            'Partnere' => 'partnere',
            'Gestus_aid' => 'Gestus hjælp',
            'gestus_staff' => 'Folkene bag'

        );

        $this->js_defer = array(
            'thickbox-js' ,
            'my-jquery' , 
            'zip_fetcher' , 
            'media-upload',
            'hoverintent-js-js',
            'wpcf7-recaptcha-js',
            'google-recaptcha-js',
            'divi-custom-script-js',
            'autoptimize-toolbar-js',
            'et-core-common-js',
            'wp-embed-js',
            'thickbox-js',
            'underscore-js',
            'shortcode-js',
            'media-upload-js',
            'es6-promise-js',
            'et-core-api-spam-recaptcha-js-extra',
            'et-core-api-spam-recaptcha-js',
            'thickbox-js-extra',
            'jquery-core-js');


        $this->managers = array(


           'managers' => array( 
            
                'page_settings_manager' => array( 
                'description' => 'Aktiver generelle side indstillinger', 
                'type' => 'settings',
                'as_controllor' => true),

                'page_menu_manager' => array( 
                'description' => 'Aktiver indstillinger for admin menuer', 
                'type' => 'admin_menu',
                'as_controllor' => true),
            
                'gestus_cpt_manager' => array( 
                'description' => 'Aktiver CPT Mananager', 
                'type' => 'post_type',
                'as_controllor' => true),
            
                'gestus_tax_manager' => array(
                'description' => 'Aktiver taxonomy Mananager', 
                'type' => 'taxonomy',
                'as_controllor' => true),
            
                'testamonial_manager' => array(
                'description' => 'Aktiver testamonial Mananager', 
                'type' => 'testamony',
                'as_controllor' => true),
            
                'aid_manager' => array( 
                'description' => 'aid_manager', 
                'type' => 'aid',
                'as_controllor' => true),
            
                'gestus_news_manager' => array(
                'description' => 'Aktiver Gestus news manager', 
                'type' => 'news',
                'as_controllor' => true),
            
                'management_manager' => array(
                'description' => 'Aktiver management Mananager', 
                'type' => 'management',
                'as_controllor' => true),
            
                'volunteer_manager' => array( 
                'description' => 'Aktiver frivillig Mananager', 
                'type' => 'volunteer',
                'as_controllor' => true),
            
                'partner_manager' => array( 
                'description' => 'Aktiver partner Mananager', 
                'type' => 'partner',
                'as_controllor' => true),

                'teen_manager' => array( 
                'description' => 'Aktiver teen Mananager', 
                'type' => 'teen',
                'as_controllor' => true),
            
                'erhvervspartner_manager' => array(
                'description' => 'Aktiver erhvervspartner Mananager', 
                'type' => 'erhvervspartner',
                'as_controllor' => true),
            
                'roles_manager' => array(
                'description' => 'Aktiver bruger roller', 
                'type' => 'userroles',
                'as_controllor' => true),
            
                'gestus_email_manager' => array( 
                'description' => 'Aktiver e-mail manager', 
                'type' => 'emails',
                'as_controllor' => true),

                'templates_manager' => array(
                'description' => 'Aktiver template manager',
                'type' => 'templates',
                'as_controllor' => true),

                'media_widget' => array(
                    'description' => 'Aktiver widget manager << not ready yet',
                    'type' => 'widgets',
                    'as_controllor' => true),

                'set_email_type' => array(
                    'description' => 'Email type in email manager',
                    'type' => 'email_type',
                    'as_controllor' => false
                ),
                'use_mails_on_posttype' => array(
                    'description' => 'Email type in email manager',
                    'type' => 'post_types',
                    'as_controllor' => false


                ),
                'gestus_query_manager' => array(
                    'description' => 'Email type in email manager',
                    'type' => 'query',
                    'as_controllor' => true


                ),
                'pickup_manager' => array(
                    'description' => 'Definere steder for afhenting',
                    'type' => 'pickup',
                    'as_controllor' => false


                )
            )
         );



        $this->page_templates = array(

            // page templates for new pages, add new ones if needed
            'page-templates/two-columns.php' => 'Two Columns Layout',
            'page-templates/three-columns.php' => 'Three Columns Layout',
            
        );

        $this->membertypes = array(

            // used in dropdown select 
            '' => 'Vælg poistion',
            'Formand' => 'Formand',
            'Næstformand' => 'Næstformand',
            'Bestyrelsesmedlem' => 'Bestyrelsesmedlem',
            'sekundant' => 'sekundant',
            'Kasserer' => 'Kasserer',
            'sekretær' => 'sekretær'

        );

        $this->check_outdated_post = array(

           'gestus_aid' => '_gestus_aid_key',

        );

        add_action('wp_enqueue_scripts', array($this, 'load_dashicons'), 999);

      

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $this->ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $this->ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])){
            $this->ip = $_SERVER['REMOTE_ADDR'];
        }else $this->ip = $_SERVER['SERVER_ADDR'];


        add_action( 'wp_enqueue_scripts', array($this, 'form_style_sheet' ));

        add_action( 'wp_enqueue_scripts', array($this, 'form_valid_script' ));

        add_action( 'wp_enqueue_scripts', array($this, 'form_aid_creator' ));

        add_action( 'wp_enqueue_scripts', array($this, 'zip_fetcher' ));

        add_filter( 'plugin_action_links', array( $this ,'disable_plugin_deactivation'), 10, 4 );

        add_filter( 'map_meta_cap', array($this, 'prevent_post_deletion'), 10, 4 );

    

        add_filter('upload_mimes', array($this, 'cc_mime_types'));

        add_filter( 'wp_check_filetype_and_ext', array($this , 'svg_support'), 10, 4 );

        add_action( 'admin_head', array($this, 'fix_svg' ));

       

    }


    public function defer_parsing_of_js ( $url ) {

  
        if ( FALSE === strpos( $url, '.js' ) ) return $url;
        if ( strpos( $url, 'jquery.js' ) ) return $url;
        return "$url' defer ";
        
        }
        

   

    
    function load_dashicons(){
        wp_enqueue_style('dashicons');
    }

    public function activated (string $key)
    {

        $option = get_option('gestus_nord_admin');

        return isset($option[ $key ]) ? $option[ $key ] : false;

    }


/**
 * Registers a stylesheet.
 */
function form_style_sheet() {

    wp_register_style( 'form_style_sheet', "$this->plugin_url/assets/forms.css" );
    
}
// Register style sheet.



/**
 * Registers form validation script.
 */
function form_valid_script() {

    wp_register_script( 'form_valid_script', "$this->plugin_url/assets/form.js" );
    
}
// Register form validation script.

/**
 * Registers form aid creator script.
 */
function form_aid_creator() {

    wp_register_script( 'form_aid_creator', "$this->plugin_url/assets/js/aid.js" );
    
}
// Register form aid creator script.

/**
 * Registers zip fetcher script.
 */
function zip_fetcher() {

    wp_register_script( 'zip_fetcher', "$this->plugin_url/assets/fetch_zipcodes.js" );
    
}
// Register zip fetcher script.





public function disable_plugin_deactivation( $actions, $plugin_file, $plugin_data, $context ) {
 
    if ( array_key_exists( 'deactivate', $actions ) && in_array( $plugin_file, array(
        'gestus_nord/gestus_nord.php'
    )))
        unset( $actions['deactivate'] );
    return $actions;
}


public function prevent_post_deletion( $caps, $cap, $user_id, $args )
{
    // Nothing to do
    if( 'delete_post' !== $cap || empty( $args[0] ) )
        return $caps;

    // Target the payment and transaction post types
    if( in_array( get_post_type( $args[0] ), $this->prevent_post_deletion , true ) )
        $caps[] = 'do_not_allow';       

    return $caps;    
}

   

public function cc_mime_types($mimes) { 
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
   }
   



   public function svg_support($data, $file, $filename, $mimes) {
 
    global $wp_version;
    if ( $wp_version !== '4.7.1' ) {
       return $data;
    }
   
    $filetype = wp_check_filetype( $filename, $mimes );
   
    return [
        'ext'             => $filetype['ext'],
        'type'            => $filetype['type'],
        'proper_filename' => $data['proper_filename']
    ];
   
  }

  public function fix_svg() {
    echo '<style type="text/css">
          .attachment-266x266, .thumbnail img {
               width: 100% !important;
               height: auto !important;
          }
          </style>';
  }
  
 
  

}
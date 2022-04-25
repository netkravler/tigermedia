<?php


/**
 * 
 * @package gestus_nord
 */

namespace Inc\Base;

use Inc\Base\BaseController;

use DirectoryIterator;

class Enqueue extends BaseController
{

    public $defer = array();

    public  function register(){




            add_action('admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts'));
            //add_action('admin_enqueue_scripts', array( $this, 'admin_enqueue_styles'));

            add_action('wp_enqueue_scripts', array( $this, 'public_enqueue_scripts'));
           // add_action('wp_enqueue_scripts', array( $this, 'public_enqueue_styles'));

            add_action('wp_enqueue_scripts' , array($this, 'wp_public_register_styles'));
            add_action('wp_enqueue_scripts' , array($this, 'wp_public_register_scripts'));

            add_filter( 'wpcf7_load_js', '__return_false' );
            add_filter( 'wpcf7_load_css', '__return_false' );
            

            add_action('wp_enqueue_scripts', array($this, 'load_wpcf7_scripts'));
            add_action('wp_print_scripts', array($this, 'load_recaptcha'));

            add_filter( 'script_loader_tag', array( $this, 'mind_defer_scripts'), 10, 3 );
    }


          
      function load_wpcf7_scripts() {
        if ( is_page('kontakt') ) {
          if ( function_exists( 'wpcf7_enqueue_scripts' ) ) {
            wpcf7_enqueue_scripts();
          }
          if ( function_exists( 'wpcf7_enqueue_styles' ) ) {
            wpcf7_enqueue_styles();
          }
        }
      }

      public function load_recaptcha() {
        //Add pages you want to allow to array
        if ( !is_page( array( 'kontakt' ) ) ){
         wp_dequeue_script( 'google-recaptcha' );
         wp_dequeue_script( 'google-invisible-recaptcha' );
        }

       }
  






    public function wp_public_register_scripts()
    {

      $this->defer[] = $this->js_defer;

      $path = get_home_path() . 'wp-content/plugins/gestus_nord/common/assets';

      $dirs = array();
      
      // directory handle
      $dir = dir($path);
      
      while (false !== ($entry = $dir->read())) {
          if ($entry != '.' && $entry != '..') {
             if (is_dir($path . '/' .$entry)) {
                  $dirs[] = $entry; 
             }
          }
      }

      foreach($dirs as $dir ){



        

      
       if (file_exists( get_home_path() . 'wp-content/plugins/gestus_nord/common/assets/'.$dir.'/js/'.$dir.'-min.js')){

        $this->defer[] .= $dir.'-script';

               
       
        wp_register_script( $dir.'-script', $this->plugin_url . 'common/assets/'.$dir.'/js/'.$dir.'-min.js');






       
      }

      if (file_exists( get_home_path() . 'wp-content/plugins/gestus_nord/common/assets/'.$dir.'/css/'.$dir.'.css')){


        wp_register_style( $dir.'-style', $this->plugin_url . 'common/assets/'.$dir.'/css/'.$dir.'.css');
 
        
       }
        
        //var_dump($this->defer);

      }
      


      wp_register_script('zip_fetcher', $this->plugin_url . 'common/js/fetch_zipcodes.js');


    }

    public function wp_public_register_styles()
    {


        wp_register_style( 'load-fa', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );

        wp_register_style('gestus_style_sheet', $this->plugin_url . 'assets/public/public_style.css');

       
     

    }


    public function wp_admin_register_scripts()
    {
          
   

    }

    public function wp_admin_register_styles()
    {


    }



    
    function admin_enqueue_scripts(){

      wp_enqueue_script('media-upload');
      wp_enqueue_media();
      wp_enqueue_style('gestus_style_sheet', $this->plugin_url . 'assets/admin/admin_styles.css');
     
      wp_enqueue_script('gestus_scripts', $this->plugin_url .  'assets/concat_admin-min.js');

      wp_enqueue_style( 'load-fa', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );

      wp_enqueue_script('admin_init', $this->plugin_url .  'assets/js/hide_visibility.js');

  }

  function public_enqueue_scripts(){
    global $post_type;
  




    
    if ( 'erhvervspartner' == $post_type )
    wp_enqueue_script('media-upload');
    wp_enqueue_script( 'my-jquery', $this->plugin_url . 'common/js/jquery.min.js', array('media-upload') );


    }


   public  function mind_defer_scripts( $tag, $handle, $src ) {

          //var_dump($this->defer);


      $defer = $this->defer;

      if ( in_array( $handle, $defer ) ) {

         return '<script src="' . $src . '" defer="defer" type="text/javascript"></script>' . "\n";
      }
        
        return $tag;
    } 
    

      
}

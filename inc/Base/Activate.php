<?php

/**
 * 
 * @package gestus_nord
 */

namespace Inc\Base;

class Activate
{

    

    function __construct()
    {
        
        register_activation_hook( __FILE__ , array ( $this  , 'activate'));

    }
    

    public static function activate(){

        flush_rewrite_rules(  );

       $default = array();




       
       if ( ! get_option('gestus_nord_admin')){

        update_option('gestus_nord_admin', $default);

       }

       if ( ! get_option('gestus_nord_admin_settings')){

        update_option('gestus_nord_admin_settings', $default);

       }

       if ( ! get_option('gestus_settings_manager')){

        update_option('gestus_settings_manager', $default);

       }
              
       if ( ! get_option('gestus_cpt_manager')){

        update_option('gestus_cpt_manager', $default);

       }

       if ( ! get_option('gestus_tax_manager')){

        update_option('gestus_tax_manager', $default);

       }

       if ( ! get_option('gestus_news_manager')){

        update_option('gestus_news_manager', $default);

       }

       if ( ! get_option('templates_manager')){

        update_option('templates_manager', $default);

       }

       if ( ! get_option('volunteer_manager')){

        update_option('volunteer_manager', $default);

       }

       if ( ! get_option('gestus_email_manager')){

        update_option('gestus_email_manager', $default);

       }

       if ( ! get_option('use_mails_on_posttype')){

        update_option('use_mails_on_posttype', $default);

       }

       if ( ! get_option('page_menu_manager')){

        update_option('page_menu_manager', $default);

       }

       if ( ! get_option('set_email_type')){

        update_option('set_email_type', $default);

       }

       if ( ! get_option('public_email_tags')){

        update_option( 'public_email_tags', $default );

        }

        if ( ! get_option('public_email_tags')){

            update_option( 'public_email_tags', $default );

        }

        if ( ! get_option('posttype_email_tags')){

            update_option( 'posttype_email_tags', $default );

        }

        if ( ! get_option('teen_manager')){

            update_option( 'teen_manager', $default );

        }



              





        
    }



}

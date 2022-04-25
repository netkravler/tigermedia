<?php

namespace Inc\Tools;

use Inc\Base\BaseController;
use WP_Query;
use WP_Roles;

class Tools extends BaseController
{


    public function register(){


        add_action('init', array($this, 'disabble_form'));

        add_action('init',array( $this,  'cloneRole' ));

        add_action('check_admin_referer', array($this, 'logout_without_confirm'), 10, 2);

        add_filter( 'wp_nav_menu_items', array($this, 'new_nav_menu_items' ));

    }


    
   

    public function cloneRole()
    {
        global $wp_roles;
        if ( ! isset( $wp_roles ) )
            $wp_roles = new WP_Roles();
    
        $adm = $wp_roles->get_role('subscriber');
        //Adding a 'new_role' with all admin caps
        $wp_roles->add_role('Erhvervspartner', 'Erhvervspartner', $adm->capabilities);
    }


    public function disabble_form(){

        
        //check_outdatedarrya in basecontroller, add new key value pair if needed
        foreach($this->check_outdated_post as $posttype => $meta_key)
        {


        $query = new WP_Query(array(
            'post_type' => $posttype,
            
        ));
        

        $now = strtotime(current_time($this->timeformat));

        if ($query->have_posts() ) : 
        
            
        while ( $query->have_posts() ) : $query->the_post();
                
        $data = get_post_meta( get_the_ID(), $meta_key, true );

        //check if visibility time is set, if any error this is properly the culprit. add these to the meta for new post
           
        
        $start = isset($data['start_time']) ? strtotime($data['start_time']) : '';
        $end = isset($data['end_time']) ? strtotime($data['end_time']) : '';
        
            //check if is set between two user defined times and set to expire
            if (($now >= $start) && ($now < $end) && isset($data['active_time']) && $data['active_time'] == 1 ){

                //publish if within time
                $this->change_post_status(get_the_ID(), 'publish');

            //if they are NOT between the two times and is set to expire
            }else if((!($now >= $start && $now < $end)) && isset($data['active_time']) && $data['active_time'] == 1){
                    
                //set to draft if not within time
                $this->change_post_status(get_the_ID(), 'draft');
            
        }
        endwhile;
        
        
        wp_reset_postdata();
        endif;
    
        }

            
        }


        // Filter wp_nav_menu() to add additional links and other output
function new_nav_menu_items($items) {
     // add the home link to the end of the menu

    if ( is_user_logged_in() ) {

        $mylinks = '<li id="menu-item-610" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-610"><a href="/wp-login.php?action=logout" title="log ud">Log ud</a></li>';
      //  $mylinks .= '<li id="menu-item-605" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-605"><a href="/wp-admin/profile.php">min profil</a></li>';

        //$mylinks .= '<li id="menu-item-600" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-600"><a href="/all-auctions/">Auktioner</a></li>';
 
     } else {

     $mylinks = '';
             }



    $items = $mylinks . $items  ;
    return $items;
}


function logout_without_confirm($action, $result)
{
    /**
     * Allow logout without confirmation
     */
    if ($action == "log-out" && !isset($_GET['_wpnonce'])) {
        $redirect_to = isset($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : wp_login_url();
        $location = str_replace('&amp;', '&', wp_logout_url($redirect_to));
        header("Location: $location");
        die;
    }
}
        


}     
    
    
<?php

namespace Inc\Base;

use WP_User;
use Inc\Base\BaseController;

class SubscripersController extends BaseController
{

public $users = array();

public function register()
{

    $this->users = array(

        array('nick' => 'anton', 'email' => 'psyk@netkravler.com' ),
        array('nick' => 'wanko', 'email' => 'stefan@netkravler.com' ),
        array('nick' => 'fury', 'email' => 'facebook@netkravler.com' )
    );

    add_action('admin_init', array($this, 'create_subscribers'));

}

function create_subscribers() {


    foreach ( $this->users as $user ) :

        $i = 1;

            if ( null == username_exists( $user['email'][$i] ) ) :

                // Generate the password and create the user
                $password = wp_generate_password( 12, false );
                $user_id  = wp_create_user( $user['nick'][$i], $password, $user['email'][$i] );

                // Set the nickname
                wp_update_user(
                    array(
                        'ID'       =>    $user_id,
                        'nickname' =>    $user['nick'][$i]
                    )
                );

                // Set the role
                $user = new WP_User( $user_id );
                $user->set_role( 'subscriber' );

                // Email the user
                wp_mail( $user['email'][$i], 'Welcome!', 'Your Password: ' . $password );

            endif;
           
            $i++;
    endforeach;
}

}

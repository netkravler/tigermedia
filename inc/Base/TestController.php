<?php

namespace Inc\Base;

use Inc\Base\BaseController;

use Inc\Base\ControllerTypeMailTags;

class TestController extends BaseController
{

    public $mail_tagger;

    public $scaffold;

    public $email_args = array();

    public function register()
    {


        $this->scaffold = array(

            'fornavn' => 'fornavn',
            'efternavn' => 'efternavn',
            'telefonnummer' => 'telefonnummer'
        );

        $this->mail_tagger = new ControllerTypeMailTags();

       // add_action('admin_init', array($this, 'add_emails'));

    }

    public function add_emails(){


        $this->email_args = array(

            'option_name'   => 'email_scaffold',
            'parent_name'   => 'email_tags',
            'post_type'     => 'partner',
            'scaffold'      => $this->scaffold 
    
        );

         $this->mail_tagger->mail_tag_sanitizer( $this->email_args );
    }

   
}

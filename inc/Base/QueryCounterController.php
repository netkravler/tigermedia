<?php

namespace Inc\Base;

use WP_Query;

use Inc\Api\SettingsApi;
use Inc\Base\BaseController;
use Inc\Api\Callbacks\AdminCallbacks;
use Inc\Api\Callbacks\FormFieldCallback;


class QueryCounterController extends BaseController
{


    public $settings;

    public $callbacks;    

    public $form_field_callback; 
    public function register()
    {


        $this->settings = new SettingsApi();

        $this->callbacks = new AdminCallbacks();



        $this->form_field_callback = new FormFieldCallback();

        $this->setSubpages();

        $this->setSettings();

        $this->setSections();

       // $this->setFields();


        $this->settings->addSubPages( $this->subpages )->register();

    }

    public function setSubpages()
    {

        $this->subpages = [
            [
 
             'parent_slug' => 'edit.php?post_type=applicants',
             'page_title' => 'Query manager',
             'menu_title' => 'Afhentning', 
             'capability' => 'manage_options',
             'menu_slug' => 'query_counter',
             'callback' => array( $this->callbacks, 'queryCounter')
 
            ]
     ];

    }

    public function setSettings(){


        $args = array(

             array(
            'option_group' => 'gestus_query_settings',
            'option_name' => 'gestus_query_manager',
            'callback' => array($this->form_field_callback  , 'Sanitize')
             )

        );


        

        

        $this->settings->setSettings( $args );
    }


    public function setSections(){


        $args = array(

            array(

                'id' => 'gestus_query_settings', // unik id
                'title' => 'Tæl indhold af querie´s',
               // 'callback' => array($this->callbacks , 'sectionsOptionsGroup_settings') ,
                'page' => 'gestus_query_manager'

            )
        );

        $this->settings->setSections( $args );
    }


}

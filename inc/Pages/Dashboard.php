<?php
/**
 * 
 * @package gestus_nord
 */

namespace Inc\Pages ;

use Inc\Api\SettingsApi;
use Inc\Base\BaseController;
use Inc\Api\Callbacks\AdminCallbacks;
use Inc\Api\Callbacks\ManagerCallbacks;

class Dashboard extends BaseController
{

    public $settings;

    public $callbacks;    
    public $callbacks_mngr;

    public $pages = array();


    public function register(){

        $this->settings = new SettingsApi();

        $this->callbacks = new AdminCallbacks();

        $this->callbacks_mngr = new ManagerCallbacks();

        $this->setPages();

        $this->setSettings();

        $this->setSections();

        $this->setFields();
      

        $this->settings->addPages( $this->pages )->withSubPage( 'Dashboard' )->register();




    }


    public function setPages(){

        $this->pages = [
            
            [
            'page_title' => 'Test side',
            'menu_title' => 'Gestus Nord' , 
            'capability' => 'manage_options' ,
            'menu_slug' => 'gestus_nord_admin',//target this in set sections function
            'callback' => array( $this->callbacks, 'adminDashboard'),
            'icon_url' => 'dashicons-welcome-widgets-menus', 
            'position' => 110
            ]
        ];

    }

  


    public function setSettings(){


        $args = array(

             array(
            'option_group' => 'gestus_settings_manager',
            'option_name' => 'gestus_nord_admin',
            'callback' => array($this->callbacks_mngr , 'checkboxSanitize')
            )

        );


        

        

        $this->settings->setSettings( $args );
    }


    public function setSections(){


        $args = array(

            array(

                'id' => 'gestus_admin_dashboard', // unik id
                'title' => 'Aktiver div. enheder på siden',
                'callback' => array($this->callbacks , 'sectionsOptionsGroup') ,
                'page' => 'gestus_nord_admin'

            )
        );

        $this->settings->setSections( $args );
    }



    public function setFields(){


        $args = array();


        foreach($this->managers['managers'] as $manager => $manager_key)  {


            if($manager_key['as_controllor']){
           
                $args[] = array(
    

                    'id' => $manager,
                    'title' => $manager_key['description'],
                    'callback' => array($this->callbacks_mngr , 'checkboxField') ,
                    'page' => 'gestus_nord_admin', //menu slug for the page this field is targeting
                    'section' => 'gestus_admin_dashboard', //id for the section this field is targeting
                    'args' => array(
                        'option_name' => 'gestus_nord_admin' ,//must match this field page name !!!
                        'label_for' => $manager,// must be the same as this field id
                        'class' => 'ui-toggle miv',
                        'loop' => $this->managers
                    )
                );
            }
    }

        $this->settings->setFields( $args );
    }

}

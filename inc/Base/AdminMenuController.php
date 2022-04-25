<?php

namespace Inc\Base;

use Inc\Base\BaseController;
use Inc\Api\SettingsApi;
use Inc\Api\Callbacks\AdminCallbacks;
use Inc\Api\Callbacks\FormFieldCallback;


class AdminMenuController extends BaseController
{

    public $settings;
    public $callbacks;
    public $form_field_callback;

    public $custom_menues = array();

    public $pages = array();

public function register()
{

    if(!$this->activated('page_settings_manager') && !$this->activated('page_menu_manager')) return;

    $this->settings = new SettingsApi();
    $this->callbacks = new AdminCallbacks();

    $this->form_field_callback = new FormFieldCallback();

    $this->setPages();

    $this->setSettings();

    $this->setSections();

    $this->setFields();
  

    $this->settings->addPages( $this->pages )->register();

}



public function setSettings(){


    $args = array(

         array(
        'option_group' => 'gestus_menu_settings',
        'option_name' => 'page_menu_manager',
        'callback' => array($this->form_field_callback  , 'Sanitize')
        )

    );


    $this->settings->setSettings( $args );
}



public function setSections(){

    $args = array(

        array(

            'id' => 'gestus_menu_settings', // unik id
            'title' => 'Nye admin menuer',
            //'callback' => array($this->callbacks , 'pagesettings') ,
            'page' => 'page_menu_manager'

        )
    );

    $this->settings->setSections( $args );
}



public function setFields(){

   

    $args  = array(
            
       
                    array(
    
                        'id' => 'admin_menu',
                        'title' => 'Side title',
                        'callback' => array($this->form_field_callback , 'textField') ,
                        'page' => 'page_menu_manager', //menu slug for the page this field is targeting
                        'section' => 'gestus_menu_settings', //id for the section this field is targeting
                        'args' => array(
                            'option_name' => 'page_menu_manager' ,//must match this field page name !!!
                            'label_for' => 'admin_menu',// must be the same as this field id
                            'class' => '',
                            'required' => 'required',
                            'field_type' => 'text'
                            
                        )),
                        array(
    
                            'id' => 'menu_title',
                            'title' => 'Menu title',
                            'callback' => array($this->form_field_callback , 'textField') ,
                            'page' => 'page_menu_manager', //menu slug for the page this field is targeting
                            'section' => 'gestus_menu_settings', //id for the section this field is targeting
                            'args' => array(
                                'option_name' => 'page_menu_manager' ,//must match this field page name !!!
                                'label_for' => 'menu_title',// must be the same as this field id
                                'class' => '',
                                'required' => 'required',
                                'field_type' => 'text'
                                
                            )),
                            array(
    
                                'id' => 'capability',
                                'title' => 'Egenskab krævet',
                                'callback' => array($this->form_field_callback , 'textField') ,
                                'page' => 'page_menu_manager', //menu slug for the page this field is targeting
                                'section' => 'gestus_menu_settings', //id for the section this field is targeting
                                'args' => array(
                                    'option_name' => 'page_menu_manager' ,//must match this field page name !!!
                                    'label_for' => 'capability',// must be the same as this field id
                                    'class' => '',
                                    'required' => 'required',
                                    'field_type' => 'text'
                                    
                                )),
                                array(
    
                                    'id' => 'menu_slug',
                                    'title' => 'Menu slug',
                                    'callback' => array($this->form_field_callback , 'textField') ,
                                    'page' => 'page_menu_manager', //menu slug for the page this field is targeting
                                    'section' => 'gestus_menu_settings', //id for the section this field is targeting
                                    'args' => array(
                                        'option_name' => 'page_menu_manager' ,//must match this field page name !!!
                                        'label_for' => 'menu_slug',// must be the same as this field id
                                        'class' => '',
                                        'required' => 'required',
                                        'field_type' => 'text'
                                        
                                    )),
                                    array(
    
                                        'id' => 'callback',
                                        'title' => 'Callback function',
                                        'callback' => array($this->form_field_callback , 'textField') ,
                                        'page' => 'page_menu_manager', //menu slug for the page this field is targeting
                                        'section' => 'gestus_menu_settings', //id for the section this field is targeting
                                        'args' => array(
                                            'option_name' => 'page_menu_manager' ,//must match this field page name !!!
                                            'label_for' => 'callback',// must be the same as this field id
                                            'class' => '',
                                            
                                            'field_type' => 'hidden'
                                            
                                        )),
                                        array(
    
                                            'id' => 'icon_url',
                                            'title' => 'Menu ikon',
                                            'callback' => array($this->form_field_callback , 'textField') ,
                                            'page' => 'page_menu_manager', //menu slug for the page this field is targeting
                                            'section' => 'gestus_menu_settings', //id for the section this field is targeting
                                            'args' => array(
                                                'option_name' => 'page_menu_manager' ,//must match this field page name !!!
                                                'label_for' => 'icon_url',// must be the same as this field id
                                                'class' => '',
                                                'required' => 'required',
                                                'field_type' => 'text'
                                                
                                            )),
                                            array(
    
                                                'id' => 'position',
                                                'title' => 'Menu position',
                                                'callback' => array($this->form_field_callback , 'textField') ,
                                                'page' => 'page_menu_manager', //menu slug for the page this field is targeting
                                                'section' => 'gestus_menu_settings', //id for the section this field is targeting
                                                'args' => array(
                                                    'option_name' => 'page_menu_manager' ,//must match this field page name !!!
                                                    'label_for' => 'position',// must be the same as this field id
                                                    'class' => '',
                                                    'required' => 'required',
                                                    'field_type' => 'number'
                                                    
                                                ))



        );



    $this->settings->setFields( $args );
}


public function setPages(){

return;
    if ( ! get_option('page_menu_manager')){

        return;
 
        }


     $options = get_option('page_menu_manager');

     foreach($options as $option ){

       $capability =  $option['capability'] ? $option['capability'] : 'editor'; 

    $this->pages = [
        
        [
        'page_title' => $option['admin_menu'],
        'menu_title' => $option['menu_title'] , 
        'capability' => $this->check_user_role($capability) ,
        'menu_slug' => $option['menu_slug'],
       // 'callback' => isset($option['menu_slug']) ? $option['menu_slug'] : '',
        'icon_url' => $option['icon_url'], 
        'position' => $option['position']
        ]
        ];
     }
}

}

<?php

namespace Inc\Base;

use Inc\Api\SettingsApi;
use Inc\Base\BaseController;
use Inc\Api\Callbacks\AdminCallbacks;
use Inc\Api\Callbacks\FormFieldCallback;

class PageSettingsController extends BaseController
{

    public $settings;
    public $callbacks;
    public $form_field_callback;

    public function register()
    {

        if ( !$this->activated('aid_manager')) return;

        $this->settings = new SettingsApi();
        $this->callbacks = new AdminCallbacks();

        $this->form_field_callback = new FormFieldCallback();

        $this->setSettings();

        $this->setSections();

        $this->setFields();
      

        $this->settings->register();

    }

    

    public function setSettings(){


        $args = array(

             array(
            'option_group' => 'gestus_page_settings',
            'option_name' => 'gestus_page_manager',
            'callback' => array($this->form_field_callback  , 'Sanitize')
            )

        );

        $this->settings->setSettings( $args );
    }


    public function setSections(){


        $args = array(

            array(

                'id' => 'dashboard_page_settings', // unik id
                'title' => 'Logo størrelser for partnere',
                //'callback' => array($this->callbacks , 'pagesettings') ,
                'page' => 'gestus_page_manager'

            )
        );

        $this->settings->setSections( $args );
    }



    public function setFields(){

        $option = get_option('gestus_page_manager');

        $args  = array(
                
            array(

                'id' => 'partner_logo_width',
                'title' => 'Bredde på partner logo ',
                'callback' => array($this->form_field_callback , 'textField') ,
                'page' => 'gestus_page_manager', //menu slug for the page this field is targeting
                'section' => 'dashboard_page_settings', //id for the section this field is targeting
                'args' => array(
                    'option_name' => 'gestus_page_manager' ,//must match this field page name !!!
                    'label_for' => 'partner_logo_width',// must be the same as this field id
                    'class' => '',
                    'required' => 'required',
                    'field_type' => 'text',
                    'value' => $option['partner_logo_width']
                )),
                array(

                    'id' => 'partner_logo_height',
                    'title' => 'Højde på partner logo ',
                    'callback' => array($this->form_field_callback , 'textField') ,
                    'page' => 'gestus_page_manager', //menu slug for the page this field is targeting
                    'section' => 'dashboard_page_settings', //id for the section this field is targeting
                    'args' => array(
                        'option_name' => 'gestus_page_manager' ,//must match this field page name !!!
                        'label_for' => 'partner_logo_height',// must be the same as this field id
                        'class' => '',
                        'required' => 'required',
                        'field_type' => 'text',
                        'value' => $option['partner_logo_height']
                    )),
                    array(
    
                        'id' => 'num_logo_in_columns',
                        'title' => 'Antal logoer i hver colonne',
                        'callback' => array($this->form_field_callback , 'textField') ,
                        'page' => 'gestus_page_manager', //menu slug for the page this field is targeting
                        'section' => 'dashboard_page_settings', //id for the section this field is targeting
                        'args' => array(
                            'option_name' => 'gestus_page_manager' ,//must match this field page name !!!
                            'label_for' => 'num_logo_in_columns',// must be the same as this field id
                            'class' => '',
                            'required' => 'required',
                            'field_type' => 'text',
                            'value' => $option['num_logo_in_columns']
                        )),
                        array(
        
                            'id' => 'num_logo_columns_mobile',
                            'title' => 'Antal logoer i hver colonne mobiludgave',
                            'callback' => array($this->form_field_callback , 'textField') ,
                            'page' => 'gestus_page_manager', //menu slug for the page this field is targeting
                            'section' => 'dashboard_page_settings', //id for the section this field is targeting
                            'args' => array(
                                'option_name' => 'gestus_page_manager' ,//must match this field page name !!!
                                'label_for' => 'num_logo_columns_mobile',// must be the same as this field id
                                'class' => '',
                                'required' => 'required',
                                'field_type' => 'text',
                                'value' => $option['num_logo_columns_mobile']
                            ))
    


            );
    
    

        $this->settings->setFields( $args );
    }


}

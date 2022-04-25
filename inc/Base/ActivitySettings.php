<?php
/**
 * 
 * @package gestus_nord
 */

namespace Inc\Base ;

use Inc\Api\SettingsApi;
use Inc\Base\BaseController;
use Inc\Api\Callbacks\AdminCallbacks;
use Inc\Api\Callbacks\Settings_Callback;


class ActivitySettings extends BaseController
{

    public $settings;

    public $callbacks;    

    public $setcallbacks;

    public $pages = array();

    public $pagesettings = array();

   // public $subpages = array();

  





    public function register(){


        add_action('init', (array( $this, 'gestus_activities')));

        $this->settings = new SettingsApi();

        $this->callbacks = new AdminCallbacks();

        $this->setcallbacks = new Settings_Callback();

    

       

       

        $this->setSettings();

        $this->setSections();

        $this->setFields();
      

        $this->settings->register();

        add_action('manage_activities_posts_columns', array( $this , 'sort_order_custom_data'));

        add_action('manage_activities_posts_custom_column', array( $this , 'sort_order_custom_columns'), 10 , 2);
 
        add_filter('manage_edit_activities_sortable_columns' , array( $this, 'sortable_order_rows'));


    }


    public function gestus_activities()
    {


        $labels = array(

            'name' => 'Activities',
            'singular_name' => 'activity',
            'menu_name' => 'Gestus aktiviteter'
        );
        
        $args = array(

            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-buddicons-replies',
            'exclude_from_search' => true,
            'show_in_menu' => 'gestus_nord_admin',
            'publicly_queryable' => true,
            'support' => array('title', 'editor' ),
            'capabilities' => array('create_posts' => false),
            'map_meta_cap' => true,
            'show_in_rest' => true,
            'supports' =>array('title', 'editor', 'page-attributes')
            
        );

        
        register_post_type('activities', $args);}

  


    public function setSettings(){


        $args = array(

             array(
            'option_group' => 'gestus_manager_settings',
            'option_name' => 'gestus_settings_manager',
            'callback' => array($this->setcallbacks  , 'settingsSanitize')
            )

        );


        

        

        $this->settings->setSettings( $args );
    }


    public function setSections(){


        $args = array(

            array(

                'id' => 'gestus_admin_dashboard_settings', // unik id
                'title' => 'Opret aktiviteter på siden',
                'callback' => array($this->callbacks , 'sectionsOptionsGroup_settings') ,
                'page' => 'gestus_settings_manager'

            )
        );

        $this->settings->setSections( $args );
    }



    public function setFields(){


        $args  = array(
                
            array(

                'id' => 'activity',
                'title' => 'Gestus aktivitet ',
                'callback' => array($this->setcallbacks , 'textField') ,
                'page' => 'gestus_settings_manager', //menu slug for the page this field is targeting
                'section' => 'gestus_admin_dashboard_settings', //id for the section this field is targeting
                'args' => array(
                    'option_name' => 'gestus_settings_manager' ,//must match this field page name !!!
                    'label_for' => 'activity',// must be the same as this field id
                    'class' => '',
                    'required' => 'required',
                    'field_type' => 'text',
                    'manager' => 'page_settings_manager'
                )),
                    array(
    
                        'id' => 'description',
                        'title' => 'Aktiviteten omhandler',
                        'callback' => array($this->setcallbacks , 'textField') ,
                        'page' => 'gestus_settings_manager', //menu slug for the page this field is targeting
                        'section' => 'gestus_admin_dashboard_settings', //id for the section this field is targeting
                        'args' => array(
                            'option_name' => 'gestus_settings_manager' ,//must match this field page name !!!
                            'label_for' => 'description',// must be the same as this field id
                            'class' => 'ui-toggle',
                            'required' => '',
                            'field_type' => 'text'
                        )),
                array(

                    'id' => 'attest',
                    'title' => 'Børne attest påkrævet',
                    'callback' => array($this->setcallbacks , 'checkboxField') ,
                    'page' => 'gestus_settings_manager', //menu slug for the page this field is targeting
                    'section' => 'gestus_admin_dashboard_settings', //id for the section this field is targeting
                    'args' => array(
                        'option_name' => 'gestus_settings_manager' ,//must match this field page name !!!
                        'label_for' => 'attest',// must be the same as this field id
                        'class' => 'ui-toggle',
                        'required' => '',
                        'field_type' => 'checkbox'
                    )),
                    array(
    
                        'id' => 'is_active',
                        'title' => 'Sæt aktiviten som aktiv',
                        'callback' => array($this->setcallbacks , 'checkboxField') ,
                        'page' => 'gestus_settings_manager', //menu slug for the page this field is targeting
                        'section' => 'gestus_admin_dashboard_settings', //id for the section this field is targeting
                        'args' => array(
                            'option_name' => 'gestus_settings_manager' ,//must match this field page name !!!
                            'label_for' => 'is_active',// must be the same as this field id
                            'class' => 'ui-toggle',
                            'required' => '',
                            'field_type' => 'checkbox'
                        )),
                        array(
        
                            'id' => 'page_id',
                            'title' => '',
                            'callback' => array($this->setcallbacks , 'textField') ,
                            'page' => 'gestus_settings_manager', //menu slug for the page this field is targeting
                            'section' => 'gestus_admin_dashboard_settings', //id for the section this field is targeting
                            'args' => array(
                                'option_name' => 'gestus_settings_manager' ,//must match this field page name !!!
                                'label_for' => 'page_id',// must be the same as this field id
                                'class' => '',
                                'field_type' => 'hidden',
                                'required' => ''
                            ))
    


            );
    
    

        $this->settings->setFields( $args );
    }




    public function sort_order_custom_data( $columns )
    {

        //var_dump($columns);

        $title = $columns['title'];
        $date = $columns['date'];

        $author = $columns['author'];

        unset ( $columns['title'], $columns['date'], $columns['author']);
     
        $columns['title'] = $title;

        $columns['is_active'] = 'Er aktiv';

        $columns['attest_demand'] = 'Børne attest påkrævet' ;

        $columns['date'] = $date;


        return $columns;
    }

    public function sort_order_custom_columns ($column, $post_id){

       

        $options = get_option('gestus_settings_manager');

       // var_dump($post_id . ' <> ' . $options[get_the_title($post_id)]['page_id']);
        
        switch($column)
        {

            case 'attest_demand':

                 if($post_id == $options[get_the_title($post_id)]['page_id']){
                
                    echo $options[get_the_title($post_id)]['attest'] == 'true' ? '<i class="dashicons dashicons-thumbs-up" ></i>' : '<i class="dashicons dashicons-thumbs-down" ></i>';

                };

            break;

            case 'is_active':

                if($post_id == $options[get_the_title($post_id)]['page_id']){
                
                    echo $options[get_the_title($post_id)]['is_active'] == 'true' ? '<i class="dashicons dashicons-thumbs-up" ></i>' : '<i class="dashicons dashicons-thumbs-down" ></i>';

                };

            break;




        }

    }

    public function sortable_order_rows ( $columns )
    {



        $columns['approved'] = 'approved';

        $columns['approved_by'] = 'approved_by';



        return $columns;
    }



}

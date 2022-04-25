<?php


/**
 * 
 * @package gestus_nord
 */

namespace Inc\Base ;

use Inc\Api\SettingsApi;
use Inc\Base\BaseController;

use Inc\Api\Callbacks\AdminCallbacks;
use Inc\Api\Callbacks\FormFieldCallback;



class TemplateController extends BaseController
{

    public $templates;

    // array located in BaseController
    public $page_templates;



    public $settings;

    public $subpages = array();

    public $callbacks; 

    public $tmp_callbacks;

    public function register()
    {




        if ( !$this->activated('templates_manager')) return;



        $this->settings = new SettingsApi();

        $this->callbacks = new AdminCallbacks();
        
        $this->tmp_callbacks = new FormFieldCallback();


        $this->setSubpages();

        $this->setSettings();

        $this->setSections();

        $this->setFields();

        
        $this->settings->addSubPages( $this->subpages )->register();


        $this->templates = $this->page_templates;


        add_filter( 'theme_page_templates' ,array($this, 'custom_templates'));

        add_filter('template_include', array( $this, 'load_template'));


    }
/**
 * 
 * 
 * area for referencing templates starts here
 * 
 * 
 */
    public function custom_templates( $templates )
    {
        
        $templates = array_merge($templates, $this->templates);

        return $templates;

    }

    public function load_template( $template )
    {

        global $post;

        if ( ! $post )
        {
            return $template;

        }


      /*  if ( is_front_page() )
        {

            $file = $this->plugin_path . 'page-templates/front_page.php';

            if( file_exists( $file ))

            {
    
                return $file;
            }
    

        }*/

        $template_name = get_post_meta( $post->ID, '_wp_page_template', true);

        if ( ! isset($this->templates[$template_name]))
        {

            return $template;

        }

        $file = $this->plugin_path . $template_name;

        if( file_exists( $file ))

        {

            return $file;
        }


        return $template;

    }
/**
 * area for referencing templates ends here
 */
   
/**
 * 
 * area for creating new page templates starts here
 * 
 * 
 */
    public function setSubpages()
    {

        $this->subpages = [
            [
 
             'parent_slug' => 'gestus_nord_admin',
             'page_title' => 'Template Manager',
             'menu_title' => 'Template Manager', 
             'capability' => 'manage_options',
             'menu_slug' => 'templates_manager',
             'callback' => array( $this->callbacks, 'templateManager')
 
            ]
     ];

    }

    //set option group info
    public function setSettings(){


        $args = array(

             array(
            'option_group' => 'template_settings_manager',
            'option_name' => 'templates_manager',
            'callback' => array($this->tmp_callbacks , 'templateSanitize') 
            )

        );

        $this->settings->setSettings( $args );
    }

    public function setSections(){


        $args = array(

            array(

                'id' => 'gestus_template_index', // unik id
                'title' => 'Vis side skabeloner på siden',
                //'callback' => array($this->tmp_callbacks , 'templateSectionManager') ,
                'page' => 'templates_manager'

            )
        );

        $this->settings->setSections( $args );
    }


    public function setFields(){
        

        $args = array(

            array(
                'id' => 'template',
                'title' => 'Navn på skabelon',
                'callback' => array($this->tmp_callbacks , 'textField') ,
                'page' => 'templates_manager', //menu slug for the page this field is targeting
                'section' => 'gestus_template_index', //id for the section this field is targeting
                'args' => array(
                    'option_name' => 'templates_manager' ,//must match this field page name !!!
                    'label_for' => 'template',
                    'placeholder' => 'Navn på skabelon',
                    'field_type' => 'text',
                    'editabble' => false,
                    'array' => 'template',
                    'class' => '',
                )
                ),
                array(
                    'id' => 'file_name',
                    'title' => 'Upload skabelon',
                    'callback' => array($this->tmp_callbacks , 'handle_file_upload') ,
                    'page' => 'templates_manager', //menu slug for the page this field is targeting
                    'section' => 'gestus_template_index', //id for the section this field is targeting
                    'args' => array(
                        'option_name' => 'templates_manager' ,//must match this field page name !!!
                        'label_for' => 'file_name',
                        'placeholder' => 'Single title',
                        'array' => 'template',
                        'class' => '',
                    )
                    ), array(
                        'id' => 'is_active_template',
                        'title' => 'Template is active',
                        'callback' => array($this->tmp_callbacks , 'checkboxField') ,
                        'page' => 'templates_manager', //menu slug for the page this field is targeting
                        'section' => 'gestus_template_index', //id for the section this field is targeting
                        'args' => array(
                            'option_name' => 'templates_manager' ,//must match this field page name !!!
                            'label_for' => 'is_active_template',
                            'class' => 'ui-toggle',
                            'array' => 'template'
                        )
                    ), array(
                        'id' => 'objects',
                        'title' => 'Post types',
                        'callback' => array($this->tmp_callbacks , 'checkboxPostTypesField') ,
                        'page' => 'templates_manager', //menu slug for the page this field is targeting
                        'section' => 'gestus_template_index', //id for the section this field is targeting
                        'args' => array(
                            'option_name' => 'templates_manager' ,//must match this field page name !!!
                            'label_for' => 'objects',
                            'class' => 'ui-toggle',
                            'array' => 'template'
                        )
                    )
        );

        $this->settings->setFields( $args );
    }





}

<?php


/**
 * 
 * @package gestus_nord
 */

namespace Inc\Base ;

use Inc\Api\SettingsApi;
use Inc\Base\BaseController;
use Inc\Api\Callbacks\FormFieldCallback;
use Inc\Api\Callbacks\AdminCallbacks;

class NewsController extends BaseController
{
    public $settings;

    public $subpages = array();

    public $storeNews = array();

    public $callbacks; 
    
    public $form_field_callback; 

    public $news = array();


    public function register()
    {


        if ( !$this->activated('gestus_news_manager')) return;

        $this->settings = new SettingsApi();

        $this->callbacks = new AdminCallbacks();
        
        $this->form_field_callback = new FormFieldCallback();

        $this->setSubpages();

        $this->setSettings();

        $this->setSections();

        $this->setFields();


        $this->settings->addSubPages( $this->subpages )->register();

        $this->storeCustomnews();



    }

    public function setSubpages()
    {

        $this->subpages = [
            [
 
             'parent_slug' => 'gestus_nord_admin',
             'page_title' => 'news manager',
             'menu_title' => 'News Manager', 
             'capability' => 'manage_options',
             'menu_slug' => 'news_manager',
             'callback' => array( $this->callbacks, 'newsManager')
 
            ]
     ];

    }

    //set option group info
    public function setSettings(){


        $args = array(

             array(
            'option_group' => 'gestus_news_settings_manager',
            'option_name' => 'gestus_news_manager',
            'callback' => array($this->form_field_callback , 'Sanitize') 
            )

        );

        $this->settings->setSettings( $args );
    }

    public function setSections(){


        $args = array(

            array(

                'id' => 'gestus_news_index', // unik id
                'title' => 'Vis news på siden',
                //'callback' => $this->FieldSection() ,
                'page' => 'gestus_news_manager'

            )
        );


  
        $this->settings->setSections( $args );
    }





    public function setFields(){
        

        $args = array(

            array(
                'id' => 'news',
                'title' => 'Custom news ID',
                'callback' => array($this->form_field_callback , 'textField') ,
                'page' => 'gestus_news_manager', //menu slug for the page this field is targeting
                'section' => 'gestus_news_index', //id for the section this field is targeting
                'args' => array(
                    'option_name' => 'gestus_news_manager' ,//must match this field page name !!!
                    'label_for' => 'news',
                    'placeholder' => 'Navn på post news',
                    'field_type' => 'text',
                    'editabble' => false,
                    'array' => 'news',
                    'class' => ''
                )
                ),
                array(
                    'id' => 'singular_name',
                    'title' => 'Singular name',
                    'callback' => array($this->form_field_callback , 'textField') ,
                    'page' => 'gestus_news_manager', //menu slug for the page this field is targeting
                    'section' => 'gestus_news_index', //id for the section this field is targeting
                    'args' => array(
                        'option_name' => 'gestus_news_manager' ,//must match this field page name !!!
                        'label_for' => 'singular_name',
                        'placeholder' => 'Single title',
                        'field_type' => 'text',
                        'editabble' => true,
                        'array' => 'news',
                        'class' => '',
                    )
                    ), array(
                        'id' => 'hierarchical',
                        'title' => 'Hierarchical',
                        'callback' => array($this->form_field_callback , 'checkboxField') ,
                        'page' => 'gestus_news_manager', //menu slug for the page this field is targeting
                        'section' => 'gestus_news_index', //id for the section this field is targeting
                        'args' => array(
                            'option_name' => 'gestus_news_manager' ,//must match this field page name !!!
                            'label_for' => 'hierarchical',
                            'class' => 'ui-toggle',
                            'array' => 'news'
                        )
                    ), array(
                        'id' => 'objects',
                        'title' => 'Post types',
                        'callback' => array($this->form_field_callback , 'checkboxPostTypesField') ,
                        'page' => 'gestus_news_manager', //menu slug for the page this field is targeting
                        'section' => 'gestus_news_index', //id for the section this field is targeting
                        'args' => array(
                            'option_name' => 'gestus_news_manager' ,//must match this field page name !!!
                            'label_for' => 'objects',
                            'class' => 'ui-toggle',
                            'array' => 'news'
                        )
                    )
        );

        $this->settings->setFields( $args );
    }


    public function storeCustomnews()
	{
		$options = get_option( 'gestus_news_manager' ) ?: array();

		foreach ($options as $option) {
			$labels = array(
				'name'              => $option['singular_name'],
				'singular_name'     => $option['singular_name'],
				'search_items'      => 'Search ' . $option['singular_name'],
				'all_items'         => 'All ' . $option['singular_name'],
				'parent_item'       => 'Parent ' . $option['singular_name'],
				'parent_item_colon' => 'Parent ' . $option['singular_name'] . ':',
				'edit_item'         => 'Edit ' . $option['singular_name'],
				'update_item'       => 'Update ' . $option['singular_name'],
				'add_new_item'      => 'Add New ' . $option['singular_name'],
				'new_item_name'     => 'New ' . $option['singular_name'] . ' Name',
				'menu_name'         => $option['singular_name'],
			);

			$this->news[] = array(
				'hierarchical'      => isset($option['hierarchical']) ? true : false,
				'labels'            => $labels,
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => $option['news'] ),
                'objects'           => isset($option['objects']) ? $option['objects'] : null,
                'show_in_rest'      => true
			);

		}
	}




   
}

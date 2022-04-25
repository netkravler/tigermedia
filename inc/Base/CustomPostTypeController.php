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


class CustomPostTypeController extends BaseController
{
    public $settings;
    public $callbacks;  
    public $subpages = array();
    public $custom_post_types = array();
    public $form_field_callback;

    public function register()
    {


        if ( !$this->activated('gestus_cpt_manager') && $this->check_user_role(array('administrator'))) return;

        $this->settings = new SettingsApi();

        $this->callbacks = new AdminCallbacks();

        $this->form_field_callback = new FormFieldCallback();

        $this->setSubpages();

        
        $this->setSettings();

        $this->setSections();

        $this->setFields();
      
        if( !empty(get_option('gestus_cpt_manager'))){
        $this->storeCustomPostTypes();
        }

        $this->settings->addSubPages( $this->subpages )->register();



        if( ! empty ($this->custom_post_types) ){

        add_action('init', array($this, 'registerCustomPostTypes'));
        }

        

    }

    //Set suppage for which this controller is to be shown
    public function setSubpages()
    {

        $this->subpages = [
            [
 
             'parent_slug' => 'gestus_nord_admin',
             'page_title' => 'CPT manager',
             'menu_title' => 'Cpt Manager', 
             'capability' => 'manage_options',
             'menu_slug' => 'cpt_manager',
             'callback' => array( $this->callbacks, 'postTypeManager')
 
            ]
        ];

    }



    
    //set option group info
    public function setSettings(){


        $args = array(

             array(
            'option_group' => 'gestus_cpt_settings_manager',
            'option_name' => 'gestus_cpt_manager',
            'callback' => array($this->form_field_callback , 'Sanitize') 
            )

        );

        $this->settings->setSettings( $args );
    }


    public function setSections(){


        $args = array(

            array(

                'id' => 'gestus_ctp_index', // unik id
                'title' => 'Vis alle Custom Post Types på siden',
                //'callback' => array($this->form_field_callback , 'cptSectionManager') ,
                'page' => 'gestus_cpt_manager'

            )
        );

        $this->settings->setSections( $args );
    }



    public function setFields(){


        $args = array(
            array(
                'id' => 'post_type',
                'title' => 'Custom Post Type ID',
                'callback' => array($this->form_field_callback , 'textField') ,
                'page' => 'gestus_cpt_manager', //menu slug for the page this field is targeting
                'section' => 'gestus_ctp_index', //id for the section this field is targeting
                'args' => array(
                    'option_name' => 'gestus_cpt_manager' ,//must match this field page name !!!
                    'label_for' => 'post_type',
                    'placeholder' => 'Navn på post type',
                    'class' => 'translations',
                    'field_type' => 'text',
                    'editabble' => false,
                    
                )
                ),
                array(
                    'id' => 'singular_name',
                    'title' => 'Singular name',
                    'callback' => array($this->form_field_callback , 'textField') ,
                    'page' => 'gestus_cpt_manager', //menu slug for the page this field is targeting
                    'section' => 'gestus_ctp_index', //id for the section this field is targeting
                    'args' => array(
                        'option_name' => 'gestus_cpt_manager' ,//must match this field page name !!!
                        'label_for' => 'singular_name',
                        'placeholder' => 'Single title',
                        'class' => 'translations',
                        'field_type' => 'text',
                        'editabble' => true
                    )
                ),
                array(
                    'id' => 'plural_name',
                    'title' => 'Plural name',
                    'callback' => array($this->form_field_callback , 'textField') ,
                    'page' => 'gestus_cpt_manager', //menu slug for the page this field is targeting
                    'section' => 'gestus_ctp_index', //id for the section this field is targeting
                    'args' => array(
                        'option_name' => 'gestus_cpt_manager' ,//must match this field page name !!!
                        'label_for' => 'plural_name',
                        'placeholder' => 'Plural title',
                        'class' => 'translations',
                        'field_type' => 'text',
                        'editabble' => true
                    )
                ),
                array(
                    'id' => 'parent_item_colon',
                    'title' => 'Parant colon',
                    'callback' => array($this->form_field_callback , 'textField') ,
                    'page' => 'gestus_cpt_manager', //menu slug for the page this field is targeting
                    'section' => 'gestus_ctp_index', //id for the section this field is targeting
                    'args' => array(
                        'option_name' => 'gestus_cpt_manager' ,//must match this field page name !!!
                        'label_for' => 'Parant colon',
                        'placeholder' => 'Paret colon',
                        'class' => 'translations',
                        'field_type' => 'text',
                        'editabble' => true
                    )
                ),


                
                array(
                    'id' => 'menu_position',
                    'title' => 'Menu position',
                    'callback' => array($this->form_field_callback , 'textField') ,
                    'page' => 'gestus_cpt_manager', //menu slug for the page this field is targeting
                    'section' => 'gestus_ctp_index', //id for the section this field is targeting
                    'args' => array(
                        'option_name' => 'gestus_cpt_manager' ,//must match this field page name !!!
                        'label_for' => 'menu_position',
                        'placeholder' => 'Menu position',
                        'class' => 'translations',
                        'field_type' => 'number',
                        'editabble' => true
                    )
                )
,
                array(
                    'id' => 'public',
                    'title' => 'Is public',
                    'callback' => array($this->form_field_callback , 'checkboxField') ,
                    'page' => 'gestus_cpt_manager', //menu slug for the page this field is targeting
                    'section' => 'gestus_ctp_index', //id for the section this field is targeting
                    'args' => array(
                        'option_name' => 'gestus_cpt_manager' ,//must match this field page name !!!
                        'label_for' => 'public',
                        'class' => 'ui-toggle'
                    )
                ),
                array(
                    'id' => 'has_archive',
                    'title' => 'Has archive',
                    'callback' => array($this->form_field_callback , 'checkboxField') ,
                    'page' => 'gestus_cpt_manager', //menu slug for the page this field is targeting
                    'section' => 'gestus_ctp_index', //id for the section this field is targeting
                    'args' => array(
                        'option_name' => 'gestus_cpt_manager' ,//must match this field page name !!!
                        'label_for' => 'has_archive',
                        'class' => 'ui-toggle'
                    )
                ),
                array(
                    'id' => 'publicly_queryable',
                    'title' => 'Er synlig for besøgende',
                    'callback' => array($this->form_field_callback , 'checkboxField') ,
                    'page' => 'gestus_cpt_manager', //menu slug for the page this field is targeting
                    'section' => 'gestus_ctp_index', //id for the section this field is targeting
                    'args' => array(
                        'option_name' => 'gestus_cpt_manager' ,//must match this field page name !!!
                        'label_for' => 'publicly_queryable',
                        'class' => 'ui-toggle'
                    )
                ),
                array(
                    'id' => 'exclude_from_search',
                    'title' => 'Gør søgbart',
                    'callback' => array($this->form_field_callback , 'checkboxField') ,
                    'page' => 'gestus_cpt_manager', //menu slug for the page this field is targeting
                    'section' => 'gestus_ctp_index', //id for the section this field is targeting
                    'args' => array(
                        'option_name' => 'gestus_cpt_manager' ,//must match this field page name !!!
                        'label_for' => 'exclude_from_search',
                        'class' => 'ui-toggle'
                    )
                ),
                array(
                    'id' => 'can_export',
                    'title' => 'Kan eksporteres',
                    'callback' => array($this->form_field_callback , 'checkboxField') ,
                    'page' => 'gestus_cpt_manager', //menu slug for the page this field is targeting
                    'section' => 'gestus_ctp_index', //id for the section this field is targeting
                    'args' => array(
                        'option_name' => 'gestus_cpt_manager' ,//must match this field page name !!!
                        'label_for' => 'can_export',
                        'class' => 'ui-toggle'
                    )
                ),
                array(
                    'id' => 'show_in_nav_menus',
                    'title' => 'Vis nav menu',
                    'callback' => array($this->form_field_callback , 'checkboxField') ,
                    'page' => 'gestus_cpt_manager', //menu slug for the page this field is targeting
                    'section' => 'gestus_ctp_index', //id for the section this field is targeting
                    'args' => array(
                        'option_name' => 'gestus_cpt_manager' ,//must match this field page name !!!
                        'label_for' => 'show_in_nav_menus',
                        'class' => 'ui-toggle'
                    )
                ),
                array(
                    'id' => 'show_in_admin_bar',
                    'title' => 'Vis adminmenu',
                    'callback' => array($this->form_field_callback , 'checkboxField') ,
                    'page' => 'gestus_cpt_manager', //menu slug for the page this field is targeting
                    'section' => 'gestus_ctp_index', //id for the section this field is targeting
                    'args' => array(
                        'option_name' => 'gestus_cpt_manager' ,//must match this field page name !!!
                        'label_for' => 'show_in_admin_bar',
                        'class' => 'ui-toggle'
                    )
                ),
                array(
                    'id' => 'show_in_menu',
                    'title' => 'Vis menu',
                    'callback' => array($this->form_field_callback , 'checkboxField') ,
                    'page' => 'gestus_cpt_manager', //menu slug for the page this field is targeting
                    'section' => 'gestus_ctp_index', //id for the section this field is targeting
                    'args' => array(
                        'option_name' => 'gestus_cpt_manager' ,//must match this field page name !!!
                        'label_for' => 'show_in_menu',
                        'class' => 'ui-toggle'
                    )
                ),
                array(
                    'id' => 'hierarchical',
                    'title' => 'Hierarkisk',
                    'callback' => array($this->form_field_callback , 'checkboxField') ,
                    'page' => 'gestus_cpt_manager', //menu slug for the page this field is targeting
                    'section' => 'gestus_ctp_index', //id for the section this field is targeting
                    'args' => array(
                        'option_name' => 'gestus_cpt_manager' ,//must match this field page name !!!
                        'label_for' => 'hierarchical',
                        'class' => 'ui-toggle'
                    )
                ),
                array(
                    'id' => 'show_ui',
                    'title' => 'Show User interface',
                    'callback' => array($this->form_field_callback , 'checkboxField') ,
                    'page' => 'gestus_cpt_manager', //menu slug for the page this field is targeting
                    'section' => 'gestus_ctp_index', //id for the section this field is targeting
                    'args' => array(
                        'option_name' => 'gestus_cpt_manager' ,//must match this field page name !!!
                        'label_for' => 'show_ui',
                        'class' => 'ui-toggle'
                    )
                ),
                array(
                    'id' => 'show_in_rest',
                    'title' => 'Show guthenberg editor',
                    'callback' => array($this->form_field_callback , 'checkboxField') ,
                    'page' => 'gestus_cpt_manager', //menu slug for the page this field is targeting
                    'section' => 'gestus_ctp_index', //id for the section this field is targeting
                    'args' => array(
                        'option_name' => 'gestus_cpt_manager' ,//must match this field page name !!!
                        'label_for' => 'show_in_rest',
                        'class' => 'ui-toggle'
                    )
                )
        );

        
        

        $this->settings->setFields( $args );
    }




    public function storeCustomPostTypes()
	{
        if ( ! get_option('gestus_nord_admin')){

           return;
    
           }


        $options = get_option('gestus_cpt_manager');

        foreach($options as $option){

           
            
		$this->custom_post_types[] = array(
			'post_type'             => $option['post_type'],
			'name'                  => $option['plural_name'],
			'singular_name'         => $option['singular_name'],
			'menu_name'             => $option['plural_name'],
			'name_admin_bar'        => $option['singular_name'],
			'archives'              => $option['singular_name']. ' Archives',
			'attributes'            => $option['singular_name']. 'Attribute',
			'parent_item_colon'     => 'Parent '. $option["singular_name"] ,
			'all_items'             => 'All '. $option['plural_name'],
			'add_new_item'          => 'Add New '. $option['singular_name'],
			'add_new'               => 'Add New',
			'new_item'              => 'New '. $option['singular_name'],
			'edit_item'             => 'Edit '. $option['singular_name'],
			'update_item'           => 'Update '. $option['singular_name'],
			'view_item'             => 'View ' . $option['singular_name'],
			'view_items'            => 'View'.  $option['plural_name'],
			'search_items'          => 'Search '. $option['singular_name'],
			'not_found'             => 'No '. $option['singular_name']. ' Found',
			'not_found_in_trash'    => 'No '. $option['singular_name']. ' in trash',
			'featured_image'        => 'Featured image',
			'set_featured_image'    => 'Set featured image',
			'remove_featured_image' => 'Remove featured image',
			'use_featured_image'    => 'Use featured image',
			'insert_into_item'      => 'Insert into '. $option['singular_name'],
			'uploaded_to_this_item' => 'Upload to '. $option['singular_name'],
			'items_list'            =>  $option['plural_name']. ' List',
			'items_list_navigation' =>  $option['plural_name'] .' List Navigation',
			'filter_items_list'     => 'Filter ' . $option['singular_name'],
			'label'                 => $option['singular_name'],
			'description'           => $option['plural_name'] . ' Custom Post Type',
			'supports'              => array('title','editor', 'thumpnail', 'category'),
			'taxonomies'            => array( 'category' , 'post_tag'),
			'hierarchical'          => !isset($option['hierarchical']) ?:false,
			'public'                => isset($option['public']) ?: false,
			'show_ui'               => isset($option['show_ui']) ?:false,
			'show_in_menu'          => isset($option['show_in_menu']) ?:false,
			'menu_position'         => isset($option['menu_position']) ,
			'show_in_admin_bar'     => isset($option['show_in_admin_bar']) ?:false,
			'show_in_nav_menus'     => isset($option['show_in_nav_menus']) ?:false,
			'can_export'            => isset($option['can_export']) ?:false,
			'has_archive'           => isset($option['has_archive']) ?:false,
			'exclude_from_search'   => isset($option['exclude_from_search']) ?:false,
			'publicly_queryable'    => isset($option['publicly_queryable']) ?:false,
            'capability_type'       => 'page',
            'show_in_rest' => !isset($option['show_in_rest']) ? : true
        );
        }
	}




	public function registerCustomPostTypes()
	{
		foreach ($this->custom_post_types as $post_type) {
			register_post_type( $post_type['post_type'],
				array(
					'labels' => array(
						'name'                  => $post_type['name'],
						'singular_name'         => $post_type['singular_name'],
						'menu_name'             => $post_type['menu_name'],
						'name_admin_bar'        => $post_type['name_admin_bar'],
						'archives'              => $post_type['archives'],
						'attributes'            => $post_type['attributes'],
						'parent_item_colon'     => $post_type['parent_item_colon'],
						'all_items'             => $post_type['all_items'],
						'add_new_item'          => $post_type['add_new_item'],
						'add_new'               => $post_type['add_new'],
						'new_item'              => $post_type['new_item'],
						'edit_item'             => $post_type['edit_item'],
						'update_item'           => $post_type['update_item'],
						'view_item'             => $post_type['view_item'],
						'view_items'            => $post_type['view_items'],
						'search_items'          => $post_type['search_items'],
						'not_found'             => $post_type['not_found'],
						'not_found_in_trash'    => $post_type['not_found_in_trash'],
						'featured_image'        => $post_type['featured_image'],
						'set_featured_image'    => $post_type['set_featured_image'],
						'remove_featured_image' => $post_type['remove_featured_image'],
						'use_featured_image'    => $post_type['use_featured_image'],
						'insert_into_item'      => $post_type['insert_into_item'],
						'uploaded_to_this_item' => $post_type['uploaded_to_this_item'],
						'items_list'            => $post_type['items_list'],
						'items_list_navigation' => $post_type['items_list_navigation'],
						'filter_items_list'     => $post_type['filter_items_list']
					),
					'label'                     => $post_type['label'],
					'description'               => $post_type['description'],
					'supports'                  => $post_type['supports'],
					'taxonomies'                => $post_type['taxonomies'],
					'hierarchical'              => $post_type['hierarchical'],
					'public'                    => $post_type['public'],
					'show_ui'                   => $post_type['show_ui'],
					'show_in_menu'              => $post_type['show_in_menu'],
					'menu_position'             => $post_type['menu_position'],
					'show_in_admin_bar'         => $post_type['show_in_admin_bar'],
					'show_in_nav_menus'         => $post_type['show_in_nav_menus'],
					'can_export'                => $post_type['can_export'],
					'has_archive'               => $post_type['has_archive'],
					'exclude_from_search'       => $post_type['exclude_from_search'],
					'publicly_queryable'        => $post_type['publicly_queryable'],
                    'capability_type'           => $post_type['capability_type'],
                    'show_in_rest' =>  $post_type['show_in_rest']
				)
			);
		}
	}
}
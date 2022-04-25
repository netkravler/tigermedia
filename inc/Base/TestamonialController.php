<?php


/**
 * 
 * @package gestus_nord
 */

namespace Inc\Base ;

use Inc\Api\SettingsApi;
use Inc\Base\BaseController;
use Inc\Api\Callbacks\ShortCodes;

class TestamonialController extends BaseController
{

    public $settings;
    public $callbacks;

    public $meta_boxes = array();




    public function register()
    {
        $this->settings = new SettingsApi();
        $this->callbacks = new ShortCodes();



        
        if ( !$this->activated('testamonial_manager')) return;


        add_action('init', (array( $this, 'gestus_testamonial')));
        
        add_action('add_meta_boxes' , array( $this, 'add_metaboxes'));

        add_action('save_post' , array( $this, 'save_meta_box'));

        add_action('manage_testamonial_posts_columns', array( $this , 'sort_order_custom_data'));

        add_action('manage_testamonial_posts_custom_column', array( $this , 'sort_order_custom_columns'), 10 , 2);
       
        add_action('wp_enqueue_scripts', array($this, 'load_dashicons'), 999);


        add_filter('manage_edit_testamonial_sortable_columns' , array( $this, 'sortable_order_rows'));

        $this->setShortcodePage();
        add_shortcode('testamonial-slideshow', array($this, 'testamonial_slideshow'));
        add_shortcode('testamonial-form', array($this, 'testamonial_form'));


       add_action('wp_ajax_submit_testamonial' , array( $this , 'submit_testamonial'));

       add_action('wp_ajax_nopriv_submit_testamonial' , array( $this , 'submit_testamonial'));
        
       

    }


    function load_dashicons(){
        wp_enqueue_style('dashicons');
    }



    public function submit_testamonial($_post)
	{

        

   
		$name = sanitize_text_field($_POST['name']);
		$email = sanitize_email($_POST['email']);
        $message = sanitize_textarea_field($_POST['message']);
        $user_ip = $_POST['user_ip'];
        $rating = $_POST['rating'];

		$data = array(
            'name' => $name,
            'user_ip' => $user_ip,
			'email' => $email,
			'approved' => 0,
            'featured' => 0,
            'rating' => $rating
		);

		$args = array(
			'post_title' => 'Hilsen fra '.$name,
			'post_content' => $message,
			'post_author' => 1,
			'post_status' => 'publish',
			'post_type' => 'testamonial',
			'meta_input' => array(
				'_gestus_testimonial_key' => $data
			)
		);

		$postID = wp_insert_post($args);

		if ($postID) {
			$return = array(
				'status' => 'success',
				'ID' => $postID
			);
			wp_send_json($return);

			wp_die();
		}

		$return = array(
			'status' => 'error'
		);
		wp_send_json($return);

		wp_die();
	}

    /**
     * 
     * define testimonial form
     */
    public function testamonial_form()
    {

        ob_start();

        wp_enqueue_style( 'load-fa' ); 

        
        wp_enqueue_style('rating-style');

        //default form stylesheet
        wp_enqueue_style( 'form-style' );  
     
    
    
        require_once("$this->plugin_path/templates/testamonials/form.php");   

        wp_enqueue_script("form_valid_script");

        wp_enqueue_script("zip_fetcher");

        return ob_get_clean();

    }



    /**
     * 
     * define path to slideshow
     */
        public function testamonial_slideshow()
    {

        ob_start();


        
        wp_enqueue_style( 'load-fa' ); 

        wp_enqueue_style('rating-style');
    
        require_once("$this->plugin_path/templates/testamonials/testamonies.php");   

    

        return ob_get_clean();

    }


    /**
     * 
     * define shortcode page in admin menu
     * 
     */
    public function setShortcodePage()
    {

        $subpage = array(

            array(

                'parent_slug' => 'edit.php?post_type=testamonial',
                'page_title' => 'shortcodes',
                'menu_title' => 'Shortcodes',
                'capability' => 'manage_options',
                'menu_slug' => 'gesus_testimonial_shortcode',
                'callback' => array( $this->callbacks , 'testimony_shortcodes')



            )
            );
            $this->settings->addSubPages( $subpage )->Register();
    }
     


    public function gestus_testamonial()
    {


        $labels = array(

            'name' => 'Testamonials',
            'singular_name' => 'testamonial',
            'menu_name' => 'Udtalelser'
        );
        
        $args = array(

            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-testimonial',
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'support' => array('title', 'editor' ),
            'capabilities' => array('create_posts' => false),
            'map_meta_cap' => true
            
        );

        
        register_post_type('testamonial', $args);}


    
    /**
     * 
     */
        public function add_metaboxes(){

        add_meta_box(
            'gestus_testamonial', //id
            'Testamonials',//title
            array( $this, 'render_meta_box'),//callback
            'testamonial', // screens / post types
            'side',// context
            'default'// priority
                // $args
                        
        );
        
    }

    public function render_meta_box($post)
    {



        wp_nonce_field( 'gestus_testimonial', 'gestus_testimonial_nonce' );

        $data = get_post_meta( $post->ID, '_gestus_testimonial_key', true );
        $user_ip = isset($data['user_ip']) ? $data['user_ip'] : '';
        $name = isset($data['name']) ? $data['name'] : '';
        $email = isset($data['email']) ? $data['email'] : '';
        $approved = isset($data['approved']) ? $data['approved'] : false;
        $featured = isset($data['featured']) ? $data['featured'] : false;
        ?>
        <p>
            <label class="meta-label" for="gestus_testimonial_ip">Bruger IP</label>
            <p class="inline strong"><?php echo $this->ip ?></p>
            
        </p>
        <p>
            <label class="meta-label" for="gestus_testimonial_author">Bruger Navn</label>
            <p class="inline strong"><?php echo esc_attr( $name ); ?></p>
            
            
        </p>
        <p>
            <label class="meta-label" for="gestus_testimonial_email">Bruger E-mail</label>
            <p class="inline strong"><?php echo esc_attr( $email ); ?></p>
            
        </p>
        <div class="meta-container">
            <label class="inline strong left" for="gestus_testimonial_approved">Godkendt</label>
            <div class="right  inline">
                <div class="ui-toggle "><input type="checkbox" id="gestus_testimonial_approved" name="gestus_testimonial_approved" value="1" <?php echo $approved ? 'checked' : ''; ?>>
                    <label for="gestus_testimonial_approved"><div></div></label>
                </div>
            </div>
        </div>
        <div class="meta-container">
            <label class="inline strong left" for="gestus_testimonial_featured">Synlig</label>
            <div class="right  inline">
                <div class="ui-toggle "><input type="checkbox" id="gestus_testimonial_featured" name="gestus_testimonial_featured" value="1" <?php echo $featured ? 'checked' : ''; ?>>
                    <label for="gestus_testimonial_featured"><div></div></label>
                </div>
            </div>
        </div>
        <?php
    }

    public function save_meta_box($post_id)
    {

        //get values not ment to be editable directly from meta data. preventing dom manipulation of input fields
        $data = get_post_meta( $post_id, '_gestus_testimonial_key', true );

        $user_ip = isset($data['user_ip']) ? $data['user_ip'] : '';
        $name = isset($data['name']) ? $data['name'] : '';
        $email = isset($data['email']) ? $data['email'] : '';
        $rating = isset($data['rating']) ? $data['rating'] : '';

        if (! isset($_POST['gestus_testimonial_nonce'])) {
            return $post_id;
        }

        $nonce = $_POST['gestus_testimonial_nonce'];
        if (! wp_verify_nonce( $nonce, 'gestus_testimonial' )) {
            return $post_id;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        if (! current_user_can( 'edit_post', $post_id ) ) {
            return $post_id;
        }

        $data = array(
            'name' => $name,
            'user_ip' =>  $user_ip,
            'email' => $email,
            'rating' => $rating,
            'approved' => isset($_POST['gestus_testimonial_approved']) ? 1 : 0,
            'featured' => isset($_POST['gestus_testimonial_featured']) ? 1 : 0,
            
        );
        update_post_meta( $post_id, '_gestus_testimonial_key', $data );
    }


    public function sort_order_custom_data( $columns )
    {


        $title = $columns['title'];
        $date = $columns['date'];

        unset ( $columns['title'], $columns['date']);
     
        $columns['title'] = $title;
        $columns['name'] = 'Anmelder navn / IP addresse';
        $columns['rating'] = 'Karaktere givet';
        $columns['approved'] = 'Godkendt';
        $columns['featured'] = 'Fremvises';
        $columns['date'] = $date;


        return $columns;
    }

    public function sort_order_custom_columns ($column, $post_id){

        $data = get_post_meta( $post_id, '_gestus_testimonial_key', true );

       
        $name = isset($data['name']) ? $data['name'] : '';
        $user_ip = isset($data['user_ip']) ? $data['user_ip'] : '';
        $email = isset($data['email']) ? $data['email'] : '';
        $rating = isset($data['rating']) ? $data['rating'] : '';
      
        $approved = isset($data['approved']) && $data['approved'] === 1 ? '<i class="dashicons dashicons-thumbs-up" ></i>' : '<i class="dashicons dashicons-thumbs-down" ></i>';

        $featured = isset($data['featured']) && $data['featured'] === 1 ? '<i class="dashicons dashicons-thumbs-up" ></i>' : '<i class="dashicons dashicons-thumbs-down" ></i>';

        switch($column)
        {
            case 'name':
                echo '<strong>' . $name . '</strong><br>'. $user_ip;
            break;

            case 'approved':
                echo $approved;
            break;

            case 'rating':

                if($rating){
                      echo $this->display_rating_stars( $rating );
                }
             
            break;


            case 'featured':
                echo $featured;
            break;
        }

    }

    public function sortable_order_rows ( $columns )
    {

        $columns['name'] = 'name';

        $columns['approved'] = 'approved';

        $columns['featured'] = 'featured';

        return $columns;
    }



}

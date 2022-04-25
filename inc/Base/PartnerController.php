<?php


/**
 * 
 * @package gestus_nord
 */

namespace Inc\Base ;

use Inc\Api\SettingsApi;
use Inc\Base\BaseController;
use Inc\Api\Callbacks\ShortCodes;

use Inc\Base\ControllerTypeMailTags;

class PartnerController extends BaseController
{

    public $email_args;

    public $mail_tagger;
    public $settings;
    public $callbacks;

    public $meta_boxes = array();


    public $data = array();

    public $secund_data = array();

    public $option_key;

    public $array_tag;


    public $posttype;


    public function register()
    {
        if ( !$this->activated('partner_manager')) return;
        $this->email_scaffold = array();

        $this->mail_tagger = new ControllerTypeMailTags();

        $this->settings = new SettingsApi();
        $this->callbacks = new ShortCodes();

        $this->posttype = 'partner';









        add_action('init', (array( $this, 'gestus_partner')));
        
        add_action('add_meta_boxes' , array( $this, 'add_metaboxes'));

        add_action('save_post' , array( $this, 'save_meta_box'));

        add_action('manage_partner_posts_columns', array( $this , 'sort_order_custom_data'));

        add_action('manage_partner_posts_custom_column', array( $this , 'sort_order_custom_columns'), 10 , 2);
       
        add_action('wp_enqueue_scripts', array($this, 'load_dashicons'), 999);


        add_filter('manage_edit_partner_sortable_columns' , array( $this, 'sortable_order_rows'));

        $this->setShortcodePage();
        add_shortcode('partner-slideshow', array($this, 'partner_slideshow'));
        add_shortcode($this->partnerform_shortcode , array($this, 'partner_form'));


       add_action('wp_ajax_submit_partner' , array( $this , 'submit_partner'));

       add_action('wp_ajax_nopriv_submit_partner' , array( $this , 'submit_partner'));
        
    
    }


    function load_dashicons(){
        wp_enqueue_style('dashicons');
    }



    public function submit_partner($_post)
	{

        

   
        $name = sanitize_text_field($_POST['name']);
        $city = sanitize_text_field($_POST['city']);
        $adresse = sanitize_text_field($_POST['adresse']);
        $email = sanitize_email($_POST['email']);
 

        $postalcode = sanitize_text_field($_POST['postalcode']);

        $phonenumber = sanitize_text_field($_POST['phonenumber']);
        $gender = sanitize_text_field($_POST['gender']);

        $mobilepay = $_POST['mobilepay'];
     
        $reminder = $_POST['reminder'];

        $user_ip = $this->ip;
        

		$this->data = array(

            
            'name' => $name,
            'email' => $email,
            'adresse' => $adresse,
            'postalcode' => $postalcode,
            'city' => $city,
            'phonenumber' => $phonenumber,
            'mobilepay' => $mobilepay,
            'reminder' => $reminder
        );
        



        $this->secund_data = array(
            'user_ip' => $user_ip,
            'gender' => $gender,
			'approved' => 0,
            'featured' => 0
        );

		$args = array(
			'post_title' => $name,
			'post_content' => '',
			'post_author' => 1,
			'post_status' => 'publish',
			'post_type' => 'partner',
			'meta_input' => array(
				'_gestus_partner_key' => array_merge( $this->data , $this->secund_data )
			)
        );
        
        $postID = wp_insert_post($args);

        $this->email_args = array(

            'option_name'   => 'email_scaffold',
            'parent_name'   => 'email_tags',
            'post_type'     => $this->posttype,
            'scaffold'      => $this->data
    
        );
         
       $this->mail_tagger->mail_tag_sanitizer( $this->email_args );



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
     * define partner form
     */
    public function partner_form()
    {

        ob_start();

        
        //gestus default form stylesheet
        wp_enqueue_style( 'gestus_style_sheet' );  

        //default form stylesheet
        wp_enqueue_style( 'form-style' );  

        wp_enqueue_style( 'load-fa' ); 
        
        require_once("$this->plugin_path/page-templates/partner_form.php");   

        wp_enqueue_script("validation-script");

        wp_enqueue_script("zip_fetcher");

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

                'parent_slug' => 'edit.php?post_type=partner',
                'page_title' => 'shortcodes',
                'menu_title' => 'Shortcodes',
                'capability' => 'manage_options',
                'menu_slug' => 'gesus_partner_shortcode',
                'callback' => array( $this->callbacks , 'part_nershortcodes')



            )
            );
            $this->settings->addSubPages( $subpage )->Register();
    }
     


    public function gestus_partner()
    {


        $labels = array(

            'name' => 'partners',
            'singular_name' => 'partner',
            'menu_name' => 'Støttemedlemmer'
        );
        
        $args = array(

            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'show_ui' => true, 
            'menu_icon' => 'dashicons-buddicons-buddypress-logo',
            'exclude_from_search' => true,
            'show_in_menu' => 'gestus_nord_admin',
            'publicly_queryable' => false,
            'supports' => array('title', 'editor', 'page-attributes' ),
            'capabilities' => array('create_posts' => false),
            'map_meta_cap' => true
            
        );

        
        register_post_type('partner', $args);}


    
    /**
     * 
     */
        public function add_metaboxes(){

        add_meta_box(
            'gestus_partner', //id
            'partners',//title
            array( $this, 'render_meta_box'),//callback
            'partner', // screens / post types
            'side',// context
            'default'// priority
                // $args
                        
        );
        
    }

    public function render_meta_box($post)
    {



        wp_nonce_field( 'gestus_partner', 'gestus_partner_nonce' );

        $data = get_post_meta( $post->ID, '_gestus_partner_key', true );


        $user_ip = isset($data['user_ip']) ? $data['user_ip'] : '';


        $name = isset($data['name']) ? $data['name'] : '';
        $email = isset($data['email']) ? $data['email'] : '';
        $gender = isset($data['gender']) ? $data['gender']: '';
        $adresse = isset($data['adresse']) ? $data['adresse'] : '';
        $postalcode = isset($data['postalcode']) ? $data['postalcode'] : '';
        $city = isset($data['city']) ? $data['city'] : '';
        $phonenumber = isset($data['phonenumber']) ? $data['phonenumber'] : '';

        $mobilepay = isset($data['mobilepay']) ? $data['mobilepay'] : false;

        $reminder = isset($data['reminder']) ? $data['reminder'] : false;


        $approved = isset($data['approved']) ? $data['approved'] : false;
        $featured = isset($data['featured']) ? $data['featured'] : false;
        ?>
        <p>
            <label class="meta-label" for="user_ip">Angivet fra IP adresse</label>
            <p class="inline strong"><?php echo $this->ip ?></p>
            
        </p>
        <p>
            <label class="meta-label" for="name">Fulde navn</label>
            <p class="inline strong"><?php echo esc_attr( $name ); ?></p>
            
            
        </p>




        <p>
            <label class="meta-label" for="adresse">Adresse</label>
            <p class="inline strong"><?php echo esc_attr( $adresse ); ?></p>
            
        </p>
        <p>
            <label class="meta-label" for="postalcode">Postnummer / by</label>
            <p class="inline strong"><?php echo esc_attr( $postalcode .' '. $city); ?></p>
            
        </p>

        <p>
            <label class="meta-label" for="email">E-mail adresse</label>
            <p class="inline strong"><?php echo esc_attr( $email ); ?></p>
            
        </p>

        <p>
            <label class="meta-label" for="phonenumber">Telefon nummer</label>
            <p class="inline strong"><?php echo esc_attr( $phonenumber ); ?></p>
            
        </p>

        <p>

            <label for="gender_man">Mand<input type="radio" id="gender_man" name="gender" value="man" class="field-input" data-type="checkbox" required="required"  <?php echo $gender == 'man' ? 'checked' : '' ;?>></label>
            <label for="gender_woman">Kvinde<input type="radio" id="gender_woman" name="gender" value="woman" class="field-input" data-type="checkbox" <?php echo $gender == 'woman' ? 'checked' : '' ;?>></label>
    
        </p>
        <div class="meta-container">
            <label class="inline strong left" for="mobilepay">Har betalt via mobilepay</label>
            <div class="right  inline">
                <div class="ui-toggle "><input type="checkbox" id="mobilepay" name="mobilepay" value="1" <?php echo $mobilepay ? 'checked' : ''; ?>>
                    <label for="mobilepay"><div></div></label>
                </div>
            </div>
        </div>

        <div class="meta-container">
            <label class="inline strong left" for="reminder">Acceptere at blive påmindet</label>
            <div class="right  inline">
                <div class="ui-toggle "><input type="checkbox" id="reminder" name="reminder" value="1" <?php echo $reminder ? 'checked' : ''; ?>>
                    <label for="reminder"><div></div></label>
                </div>
            </div>
        </div>

        <div class="meta-container">
            <label class="inline strong left" for="gestus_partner_approved">Godkendt</label>
            <div class="right  inline">
                <div class="ui-toggle "><input type="checkbox" id="gestus_partner_approved" name="gestus_partner_approved" value="1" <?php echo $approved ? 'checked' : ''; ?>>
                    <label for="gestus_partner_approved"><div></div></label>
                </div>
            </div>
        </div>
        <div class="meta-container">
            <label class="inline strong left" for="gestus_partner_featured">Synlig</label>
            <div class="right  inline">
                <div class="ui-toggle "><input type="checkbox" id="gestus_partner_featured" name="gestus_partner_featured" value="1" <?php echo $featured ? 'checked' : ''; ?>>
                    <label for="gestus_partner_featured"><div></div></label>
                </div>
            </div>
        </div>
        <?php
    }

    public function save_meta_box($post_id)
    {

        //get values not ment to be editable directly from meta data. preventing dom manipulation of input fields
        $data = get_post_meta( $post_id, '_gestus_partner_key', true );

        $user_ip = isset($data['user_ip']) ? $data['user_ip'] : '';


        $name = isset($data['name']) ? $data['name'] : '';
        $email = isset($data['email']) ? $data['email'] : '';
        $adresse = isset($data['adresse']) ? $data['adresse'] : '';
        $postalcode = isset($data['postalcode']) ? $data['postalcode'] : '';
        $city = isset($data['city']) ? $data['city'] : '';
        $phonenumber = isset($data['phonenumber']) ? $data['phonenumber'] : '';
        $gender = isset($_POST['gender']) ? $_POST['gender']: '';

        if (! isset($_POST['gestus_partner_nonce'])) {
            return $post_id;
        }

        $nonce = $_POST['gestus_partner_nonce'];
        if (! wp_verify_nonce( $nonce, 'gestus_partner' )) {
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
            'email' => $email,
            'user_ip' =>  $user_ip,
            'adresse' => $adresse,
            'postalcode' => $postalcode,
            'city' => $city,
            'phonenumber' => $phonenumber,
            'gender' => $gender,

            'mobilepay' => isset($_POST['mobilepay']) ? 1 : 0,          
            'reminder' => isset($_POST['reminder']) ? 1 : 0,

            'approved' => isset($_POST['gestus_partner_approved']) ? 1 : 0,
            'featured' => isset($_POST['gestus_partner_featured']) ? 1 : 0,
            
        );
        update_post_meta( $post_id, '_gestus_partner_key', $data );
    }


    public function sort_order_custom_data( $columns )
    {


        $title = $columns['title'];
        $date = $columns['date'];

        unset ( $columns['title'], $columns['date']);
     

        $columns['name'] = 'Anmelder navn / IP addresse';
        $columns['phonenumber'] = 'Telefonnummer';
        $columns['email'] = 'E-mail adresse';
        $columns['gender'] = 'Køn';
        $columns['approved'] = 'Godkendt';
        //$columns['featured'] = 'Fremvises';
        $columns['date'] = $date;


        return $columns;
    }

    public function sort_order_custom_columns ($column, $post_id){

        $data = get_post_meta( $post_id, '_gestus_partner_key', true );

       
        $name = isset($data['name']) ? $data['name'] : '';
        $user_ip = isset($data['user_ip']) ? $data['user_ip'] : '';
        $email = isset($data['email']) ? $data['email'] : '';
        $gend = isset($data['gender']) ? $data['gender'] : '';
        $gender = ($gend == 'man') ? '<i class="fa fa-male fa-2x" style="color:#02A3FE"></i>' : '<i class="fa fa-female fa-2x" style="color:#FF007F"></i>';
        $phone = isset($data['phonenumber']) ? $data['phonenumber'] : '';
   
      
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

            case 'gender':

                echo $gender ;

            break;

            case 'featured':
                echo $featured;
            break;


            case 'email':
                echo '<a href="mailto:'.$email.'">'.$email.'</a>';
            break;

            case 'phonenumber':
                echo '<a href="tel:'.$phone.'">'.$phone.'</a>';
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

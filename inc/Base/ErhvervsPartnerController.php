<?php


/**
 * 
 * @package gestus_nord
 */

namespace Inc\Base ;

use Inc\Api\SettingsApi;
use Inc\Base\BaseController;
use Inc\Api\Callbacks\ShortCodes;
use Inc\Tools\GetMetaByKey;
use Inc\Base\ResponseMailController;

use Inc\Base\ControllerTypeMailTags;


class ErhvervsPartnerController extends BaseController
{

    public $mail_tagger;

    public $settings;
    public $callbacks;

    public $posttype;

    public $meta_boxes = array();

    public $mailer;

    public $data = array();

    public $secund_data = array();

    public $post_type_array;

    


    public function register()
    {

        $this->mail_tagger = new ControllerTypeMailTags();
        $this->email_scaffold = array();


        $this->settings = new SettingsApi();
        $this->callbacks = new ShortCodes();

        $this->meta = new GetMetaByKey();

        $this->mailer = new ResponseMailController();

        $this->posttype = 'erhvervspartner';


        
        if ( !$this->activated('erhvervspartner_manager')) return;


        add_action('init', (array( $this, 'gestus_erhvervspartner')));
        
        add_action('add_meta_boxes' , array( $this, 'add_metaboxes'));

        add_action('save_post' , array( $this, 'save_meta_box'));

        add_action('manage_erhvervspartner_posts_columns', array( $this , 'sort_order_custom_data'));

        add_action('manage_erhvervspartner_posts_custom_column', array( $this , 'sort_order_custom_columns'), 10 , 2);
       
        add_action('wp_enqueue_scripts', array($this, 'load_dashicons'), 999);


        add_filter('manage_edit_erhvervspartner_sortable_columns' , array( $this, 'sortable_order_rows'));

        $this->setShortcodePage();

        add_shortcode('erhvervspartner-slideshow', array($this, 'erhvervspartner_slideshow'));

        add_shortcode($this->erhvervspartnerform_shortcode , array($this, 'erhvervspartner_form'));


       add_action('wp_ajax_submit_erhvervspartner' , array( $this , 'submit_erhvervspartner'));

       add_action('wp_ajax_nopriv_submit_erhvervspartner' , array( $this , 'submit_erhvervspartner'));
        
      

    }


    public function gestus_erhvervspartner()
    {

        
        $labels = array(

            'name' => 'erhvervspartners',
            'singular_name' => $this->posttype,
            'menu_name' => 'Erhvervspartnere'
        );
        
        $args = array(

            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'show_ui' => true, 
            'menu_icon' => 'dashicons-buddicons-buddypress-logo',
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'show_in_menu' => 'gestus_nord_admin',
            'supports' => array('title', 'editor', 'page-attributes'  ),
            'capabilities' => array('create_posts' => false),
            'map_meta_cap' => true
            
        );


        
        register_post_type($this->posttype, $args);
    
    }



    function load_dashicons(){
        wp_enqueue_style('dashicons');
    }



    public function submit_erhvervspartner($_post)
	{

        

   
		$company_name = sanitize_text_field($_POST['company_name']);
        $company_email = sanitize_email($_POST['company_email']);
        $faktura_email = sanitize_email($_POST['faktura_email']);

        $contact_email = sanitize_email($_POST['contact_email']);
        $newsletter_email = sanitize_email($_POST['newsletter_email']);
        $invite_email = sanitize_email($_POST['invite_email']);


        $cvr_nummer = $_POST['cvr'];
        $comapny_dep = sanitize_text_field($_POST['company_dep']);

        $contact_person = sanitize_text_field($_POST['contact_person']);
        $postalcode = sanitize_text_field($_POST['postalcode']);
        $city = sanitize_text_field($_POST['city']);
        $adresse = sanitize_text_field($_POST['adresse']);
        $phonenumber = sanitize_text_field($_POST['phonenumber']);
        $website = filter_var($_POST['website'], FILTER_SANITIZE_URL);
        $logo = $_POST['file_dir'];
        
        $user_ip = $this->ip;
        

		$this->data = array(

            
            'name' => $company_name,
            'email' => $company_email,
            'faktura_email' => $faktura_email,
            'cvr_nummer' => $cvr_nummer,
            'company_dep' => $comapny_dep,
            'contact_person' => $contact_person,

            'contact_email' => $contact_email,
            'newsletter_email' => $newsletter_email,
            'invite_email' => $invite_email,
            

            'adresse' => $adresse,
            'postalcode' => $postalcode,
            'city' => $city,
            'website' => $website,
            'phonenumber' => $phonenumber

		);

        //send array to Email tags for filtering
        $this->post_type_array = array(

            //new arrays can be added here
            $this->posttype => $this->data

        );
  

        

        $this->secund_data = array(

            'user_ip' => $user_ip,
            'logo' => $logo,
			'approved' => 1,
            'featured' => 1
        );
    


		$args = array(
			'post_title' => $company_name,
			'post_content' => '',
			'post_author' => 1,
			'post_status' => 'publish',
			'post_type' => 'erhvervspartner',
			'meta_input' => array(
				'_gestus_erhvervspartner_key' => array_merge($this->data , $this->secund_data)
			)
        );
        
        


     /*   $if_exists = $this->meta->get_meta_value_by_key('erhvervspartner', '_gestus_erhvervspartner_key', 's:10:"cvr_nummer";s:8:"'.$_POST['cvr'].'"');
        if($if_exists){

            $newargs = array(

                'ID' => $if_exists,
                'post_title' => $company_name,
                'post_content' => '',
                'post_author' => 1,
                'post_status' => 'publish',
                'post_type' => 'erhvervspartner',
                'meta_input' => array(
                    '_gestus_erhvervspartner_key' => $this->data
                ));

                wp_update_post( $newargs );

                $postID = $if_exists;

        }else{*/

            		$postID = wp_insert_post($args);
      /*  };*/

        if ( $this->activated('gestus_email_manager')) {

           // $this->mailer->sendmail('info@netkravler.com', 'tester skidtet', 'dette er min fede body');
        }
        



        $email_args = array(

            'option_name'   => 'email_scaffold',
            'parent_name'   => 'email_tags',
            'post_type'     => $this->posttype,
            'scaffold'      => $this->data
    
        );
    
        
        $this->mail_tagger->mail_tag_sanitizer( $email_args );


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
     * define erhvervspartner form
     */
    public function erhvervspartner_form()
    {

        ob_start();



        /**
         * combined scss
         * including in order
         * public-style.css
         * form.css
         * bootstrap-style
         * croppie-style
         * cdn font awsome
         * 
         */
        
        //gestus default form stylesheet
        wp_enqueue_style( 'gestus_style_sheet' );  

        //default form stylesheet
        wp_enqueue_style( 'form-style' );  

        //get bootstrap for formstyling logo upload
        wp_enqueue_style( 'bootstrap-style' );  
        //get croppie styling
        wp_enqueue_style( 'croppie-style' ); 

        wp_enqueue_style( 'load-fa' ); 

        wp_enqueue_script( 'my-jquery' );
 
          
        require_once("$this->plugin_path/page-templates/erhvervspartner_form.php");   

        wp_enqueue_script( 'bootstrap-script' , array( 'my-jquery' ));

        //concatinated scripts
        wp_enqueue_script( 'croppie-script' , array( 'my-jquery' ) );


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

                'parent_slug' => 'edit.php?post_type=erhvervspartner',
                'page_title' => 'shortcodes',
                'menu_title' => 'Shortcodes',
                'capability' => 'manage_options',
                'menu_slug' => 'gesus_erhvervspartner_shortcode',
                'callback' => array( $this->callbacks , 'erhvervs_partnershortcodes')



            )
            );
            $this->settings->addSubPages( $subpage )->Register();
    }
     


    


    
    /**
     * 
     */
        public function add_metaboxes(){

        add_meta_box(
            'gestus_erhvervspartner', //id
            'erhvervspartners',//title
            array( $this, 'render_meta_box'),//callback
            'erhvervspartner', // screens / post types
            'side',// context
            'default'// priority
                // $args
                        
        );
        
    }

    public function render_meta_box($post)
    {



        wp_nonce_field( 'gestus_erhvervspartner', 'gestus_erhvervspartner_nonce' );

        $data = get_post_meta( $post->ID, '_gestus_erhvervspartner_key', true );


        $comany_ip = isset($data['comany_ip']) ? $data['comany_ip'] : '';


        $company_name = isset($data['name']) ? $data['name'] : '';
        $company_email = isset($data['email']) ? $data['email'] : '';
        $faktura_email = isset($data['faktura_email']) ? $data['faktura_email'] : '';
        $cvr_nummer = isset($data['cvr_nummer']) ? $data['cvr_nummer'] : '';
        $company_dep = isset($data['company_dep']) ? $data['company_dep'] : '';
        $contact_person = isset($data['contact_person']) ? $data['contact_person'] : '';

        $contact_email = isset($data['contact_email']) ? $data['contact_email'] : '';
        $newsletter_email = isset($data['newsletter_email']) ? $data['newsletter_email'] : '';
        $invite_email = isset($data['invite_email']) ? $data['invite_email'] : '';

        $adresse = isset($data['adresse']) ? $data['adresse'] : '';
        $postalcode = isset($data['postalcode']) ? $data['postalcode'] : '';
        $city = isset($data['city']) ? $data['city'] : '';
        $phonenumber = isset($data['phonenumber']) ? $data['phonenumber'] : '';
        $website = isset($data['website']) ? $data['website'] : '';

        $logo = isset($data['logo']) ? $data['logo'] : '';

        $approved = isset($data['approved']) ? $data['approved'] : false;
        $featured = isset($data['featured']) ? $data['featured'] : false;
        ?>

        <img src="<?php echo $logo?>" width="100%" alt="<?php echo $company_name.'s logo'?>">
        <p>
            <label class="meta-label" for="comany_ip">Angivet fra IP adresse</label><br>
            <strong><?php echo $this->ip ?></strong>
            <hr>
        </p>
        <p>
            <label class="meta-label" for="company_name">Virksomhed</label><br>
            <strong><?php echo esc_attr( $company_name ); ?></strong>
            
            <hr>
        </p>

        <p>
            <label class="meta-label" for="cvr_nummer">CVR nummer</label><br>
            <strong><?php echo esc_attr( $cvr_nummer ); ?></strong>
            <hr>
        </p>


        <p>
            <label class="meta-label" for="company_dep">Afd</label><br>
            <strong><?php echo esc_attr( $company_dep ); ?></strong>
            <hr>
        </p>


        <p>
            <label class="meta-label" for="adresse">Adresse</label><br>
            <strong><?php echo esc_attr( $adresse ); ?></strong>
            <hr>
        </p>
        <p>
            <label class="meta-label" for="postalcode">Postnummer / by</label><br>
            <strong><?php echo esc_attr( $postalcode .' '. $city); ?></strong>
            <hr>
        </p>

        <p>
            <label class="meta-label" for="phonenumber">Telefon nummer</label><br>
            <strong><?php echo esc_attr( $phonenumber ); ?></strong>
            <hr>
        </p>

        <p>
            <label class="meta-label" for="company_email">E-mail</label><br>
            <strong><?php echo esc_attr( $company_email ); ?></strong>
            <hr>
        </p>

        <p>
            <label class="meta-label" for="faktura_email">Faktura email</label><br>
            <strong><?php echo esc_attr( $faktura_email ); ?></strong>
            <hr>
        </p>


        <p>
            <label class="meta-label" for="contact_person">Kontakt person</label><br>
            <strong><?php echo esc_attr( $contact_person ); ?></strong>
            <hr>
        </p>

        <p>
            <label class="meta-label" for="contact_email">Kontaktpersons Email</label><br>
            <strong><?php echo esc_attr( $contact_email ); ?></strong>
            <hr>
        </p>

        <p>
            <label class="meta-label" for="newsletter_email">Nyhedsbreve Email</label><br>
            <strong><?php echo esc_attr( $newsletter_email ); ?></strong>
            <hr>
        </p>

        <p>
            <label class="meta-label" for="invite_email">Indbydelser Email</label><br>
            <strong><?php echo esc_attr( $invite_email ); ?></strong>
            <hr>
        </p>


        <p>
            <label class="meta-label" for="website">Hjemmside</label><br>
            <strong><a href="<?php echo esc_attr( $website ); ?>"><?php echo esc_attr( $website ); ?></a></strong>
            <hr>
        </p>


        <div class="meta-container">
            <label class="inline strong left" for="gestus_erhvervspartner_approved">Godkendt</label>
            <div class="right  inline">
                <div class="ui-toggle "><input type="checkbox" id="gestus_erhvervspartner_approved" name="gestus_erhvervspartner_approved" value="1" <?php echo $approved ? 'checked' : ''; ?>>
                    <label for="gestus_erhvervspartner_approved"><div></div></label>
                </div>
            </div>
        </div>
        <div class="meta-container">
            <label class="inline strong left" for="gestus_erhvervspartner_featured">Synlig</label>
            <div class="right  inline">
                <div class="ui-toggle "><input type="checkbox" id="gestus_erhvervspartner_featured" name="gestus_erhvervspartner_featured" value="1" <?php echo $featured ? 'checked' : ''; ?>>
                    <label for="gestus_erhvervspartner_featured"><div></div></label>
                </div>
            </div>
        </div>
        <?php
    }

    public function save_meta_box($post_id)
    {

        //get values not ment to be editable directly from meta data. preventing dom manipulation of input fields
        $data = get_post_meta( $post_id, '_gestus_erhvervspartner_key', true );

        $company_ip = isset($data['comany_ip']) ? $data['comany_ip'] : $this->ip;


        $company_name = isset($data['name']) ? $data['name'] : '';
        $company_email = isset($data['email']) ? $data['email'] : '';
        $faktura_email = isset($data['faktura_email']) ? $data['faktura_email'] : '';
        $cvr_nummer = isset($data['cvr_nummer']) ? $data['cvr_nummer'] : '';
        $company_dep = isset($data['company_dep']) ? $data['company_dep'] : '';
        $contact_person = isset($data['contact_person']) ? $data['contact_person'] : '';

        $contact_email = isset($data['contact_email']) ? $data['contact_email'] : '';
        $newsletter_email = isset($data['newsletter_email']) ? $data['newsletter_email'] : '';
        $invite_email = isset($data['invite_email']) ? $data['invite_email'] : '';
        $logo = isset($data['logo']) ? $data['logo'] : '';
        $adresse = isset($data['adresse']) ? $data['adresse'] : '';
        $postalcode = isset($data['postalcode']) ? $data['postalcode'] : '';
        $city = isset($data['city']) ? $data['city'] : '';
        $phonenumber = isset($data['phonenumber']) ? $data['phonenumber'] : '';
        $website = isset($data['website']) ? $data['website'] : '';
        

        if (! isset($_POST['gestus_erhvervspartner_nonce'])) {
            return $post_id;
        }

        $nonce = $_POST['gestus_erhvervspartner_nonce'];
        if (! wp_verify_nonce( $nonce, 'gestus_erhvervspartner' )) {
            return $post_id;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        if (! current_user_can( 'edit_post', $post_id ) ) {
            return $post_id;
        }



        $data = array(
            'name' => $company_name,
            'email' => $company_email,
            'faktura_email' => $faktura_email,
           
            'cvr_nummer' => $cvr_nummer ,
            'company_dep' => $company_dep ,
            'contact_person' => $contact_person,
            
            'contact_email' => $contact_email,
            'newsletter_email' => $newsletter_email,
            'invite_email' => $invite_email,            


            'adresse' => $adresse,
            'postalcode' => $postalcode,
            'city' => $city,

            'website' => $website,
            'phonenumber' => $phonenumber,
            'user_ip' => $company_ip,
            'logo' => $logo,
            'approved' => isset($_POST['gestus_erhvervspartner_approved']) ? 1 : 0,
            'featured' => isset($_POST['gestus_erhvervspartner_featured']) ? 1 : 0,
            
        );
        update_post_meta( $post_id, '_gestus_erhvervspartner_key', $data );
    }


    public function sort_order_custom_data( $columns )
    {


        $title = $columns['title'];
        $date = $columns['date'];

        unset ( $columns['title'], $columns['date']);
        $columns['title'] = $title;
        $columns['afd'] = 'Afdeling';

        $columns['logo'] = 'logo';
        $columns['cvr'] = 'CVR nummer';

        $columns['contact'] = 'Kontakt person';
        $columns['fak_email'] = 'Faktura email';
        $columns['approved'] = 'Godkendt';

        //$columns['featured'] = 'Fremvises';
        $columns['date'] = $date;


        return $columns;
    }

    public function sort_order_custom_columns ($column, $post_id){

        $data = get_post_meta( $post_id, '_gestus_erhvervspartner_key', true );

        $company_ip = isset($data['comany_ip']) ? $data['comany_ip'] : $this->ip;

        $company_name = isset($data['name']) ? $data['name'] : '';
        $company_email = isset($data['email']) ? $data['email'] : '';
        $faktura_email = isset($data['faktura_email']) ? $data['faktura_email'] : '';
        $cvr_nummer = isset($data['cvr_nummer']) ? $data['cvr_nummer'] : '';
        $company_dep = isset($data['company_dep']) ? $data['company_dep'] : '';
        $contact_person = isset($data['contact_person']) ? $data['contact_person'] : '';
        $adresse = isset($data['adresse']) ? $data['adresse'] : '';
        $postalcode = isset($data['postalcode']) ? $data['postalcode'] : '';
        $city = isset($data['city']) ? $data['city'] : '';
        $phonenumber = isset($data['phonenumber']) ? $data['phonenumber'] : '';
        $logo = isset($data['logo']) ? $data['logo'] : '';

      
        $approved = isset($data['approved']) && $data['approved'] === 1 ? '<i class="dashicons dashicons-thumbs-up" ></i>' : '<i class="dashicons dashicons-thumbs-down" ></i>';

        $featured = isset($data['featured']) && $data['featured'] === 1 ? '<i class="dashicons dashicons-thumbs-up" ></i>' : '<i class="dashicons dashicons-thumbs-down" ></i>';

        switch($column)
        {
            case 'afd':

                echo $company_dep;

            break;

            case 'logo':

            echo "<img width='75px' src='$logo'>";

            break;


            case 'name':
                echo '<strong>' . $company_name . '</strong><br>'. $company_ip;
            break;

            case 'approved':
                echo $approved;
            break;

            case 'cvr':
                echo $cvr_nummer;
            break;

            case 'contact':
                echo $contact_person;
            break;


            case 'fak_email':
                echo $faktura_email;
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

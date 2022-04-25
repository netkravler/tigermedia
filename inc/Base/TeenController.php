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

class TeenController extends BaseController
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
        //if ( !$this->activated('teen_manager')) return;
        $this->email_scaffold = array();

        $this->mail_tagger = new ControllerTypeMailTags();

        $this->settings = new SettingsApi();
        $this->callbacks = new ShortCodes();

        $this->posttype = 'teen';









        add_action('init', (array( $this, 'gestus_teen')));
        
        add_action('add_meta_boxes' , array( $this, 'add_metaboxes'));

        add_action('save_post' , array( $this, 'save_meta_box'));

        add_action('manage_teen_posts_columns', array( $this , 'sort_order_custom_data'));

        add_action('manage_teen_posts_custom_column', array( $this , 'sort_order_custom_columns'), 10 , 2);
       
        add_action('wp_enqueue_scripts', array($this, 'load_dashicons'), 999);


        add_filter('manage_edit_teen_sortable_columns' , array( $this, 'sortable_order_rows'));

        $this->setShortcodePage();
        add_shortcode('teen-slideshow', array($this, 'teen_slideshow'));

        add_shortcode($this->teen_shortcode , array($this, 'teen_form'));


       add_action('wp_ajax_submit_teen' , array( $this , 'submit_teen'));

       add_action('wp_ajax_nopriv_submit_teen' , array( $this , 'submit_teen'));
        
    
    }


    function load_dashicons(){
        wp_enqueue_style('dashicons');
    }



    public function submit_teen($_post)
	{

        

        $gender = sanitize_text_field($_POST['gender']);
        $age = sanitize_text_field($_POST['age']);

        $fname = sanitize_text_field($_POST['fname']);
        $lname = sanitize_text_field($_POST['lname']);

        $adresse = sanitize_text_field($_POST['adresse']);
        $postalcode = sanitize_text_field($_POST['postalcode']);
        $city = sanitize_text_field($_POST['city']);
        $phonenumber = sanitize_text_field($_POST['phonenumber']);
        $email = sanitize_email($_POST['email']);

        $klasse = sanitize_text_field($_POST['klasse']);

        $skole = sanitize_text_field($_POST['skole']);
 
   
        $pfname = sanitize_text_field($_POST['pfname']);
        $plname = sanitize_text_field($_POST['plname']);
        $pemail = sanitize_email($_POST['pemail']);
        $pphonenumber = sanitize_text_field($_POST['pphonenumber']);

        
        $joinreason = sanitize_text_field($_POST['joinreason']);

        $user_ip = $this->ip;
        

		$this->data = array(

            
            'fname' => $fname,
            'lname' => $lname,
            'email' => $email,
            'adresse' => $adresse,
            'postalcode' => $postalcode,
            'city' => $city,
            'phonenumber' => $phonenumber,
            'skole' => $skole,
            'klasse' => $klasse,
            'pfname' => $pfname,
            'plname' => $plname,
            'pemail' => $pemail,
            'pphonenumber' => $pphonenumber,
            'joinreason' => $joinreason


        );
        



        $this->secund_data = array(
            'user_ip' => $user_ip,
            'gender' => $gender,
            'age' => $age,
			'approved' => 0,
            'featured' => 0
        );

		$args = array(
			'post_title' => $fname .' ' . $lname . ' ' . $phonenumber,
			'post_content' => '',
			'post_author' => 1,
			'post_status' => 'publish',
			'post_type' => 'teen',
			'meta_input' => array(
				'_gestus_teen_key' => array_merge( $this->data , $this->secund_data )
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
     * define teen form
     */
    public function teen_form()
    {

        ob_start();

        
        //gestus default form stylesheet
        wp_enqueue_style( 'gestus_style_sheet' );  

        //default form stylesheet
        wp_enqueue_style( 'form-style' );  

        wp_enqueue_style( 'load-fa' ); 
        
        require_once("$this->plugin_path/page-templates/teen_form.php");   

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

                'parent_slug' => 'edit.php?post_type=teen',
                'page_title' => 'shortcodes',
                'menu_title' => 'Shortcodes',
                'capability' => 'manage_options',
                'menu_slug' => 'gesus_teen_shortcode',
                'callback' => array( $this->callbacks , 'teenshortcodes')



            )
            );
            $this->settings->addSubPages( $subpage )->Register();
    }
     


    public function gestus_teen()
    {


        $labels = array(

            'name' => 'teens',
            'singular_name' => 'teen',
            'menu_name' => 'Gestus teen'
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
            'supports' => array('title', 'editor', 'page-attributes' ),
            'capabilities' => array('create_posts' => false),
            'map_meta_cap' => true
            
        );

        
        register_post_type('teen', $args);}


    
    /**
     * 
     */
        public function add_metaboxes(){

        add_meta_box(
            'gestus_teen', //id
            'Godkend Gestus Teen ',//title
            array( $this, 'render_meta_box'),//callback
            'teen', // screens / post types
            'side',// context
            'default'// priority
                // $args
                        
        );

        add_meta_box(
            'gestus_teen_reason', //id
            'Gestus Teen info',//title
            array( $this, 'render_reason_box'),//callback
            'teen', // screens / post types
            'normal',// context
            'default'// priority
                // $args
                        
        );
        
    }

    public function render_reason_box($post)
    {


        $data = get_post_meta( $post->ID, '_gestus_teen_key', true );

        $joinreason = isset($data['joinreason']) ? $data['joinreason'] : '';

        echo $joinreason;

        $fname = isset($data['fname']) ? $data['fname'] : '';
        $lname = isset($data['lname']) ? $data['lname'] : '';
        $email = isset($data['email']) ? $data['email'] : '';
        $gender = isset($data['gender']) ? $data['gender']: '';
        $age = isset($data['age']) ? $data['age']: '';
        $adresse = isset($data['adresse']) ? $data['adresse'] : '';
        $postalcode = isset($data['postalcode']) ? $data['postalcode'] : '';
        $city = isset($data['city']) ? $data['city'] : '';
        $phonenumber = isset($data['phonenumber']) ? $data['phonenumber'] : '';

        $klasse = isset($data['klasse']) ? $data['klasse'] : '';
        $skole = isset($data['skole']) ? $data['skole'] : '';

        $pfname = isset($data['pfname']) ? $data['pfname'] : '';
        $plname = isset($data['plname']) ? $data['plname'] : '';
        $pemail = isset($data['pemail']) ? $data['pemail'] : '';

        $pphonenumber = isset($data['pphonenumber']) ? $data['pphonenumber'] : '';





        ?>
        <p>
            <h4>Angivet fra IP adresse</h4>
            <p class="inline strong"><?php echo $this->ip ?></p>
            
        </p>
        <p>
           <h4>Fulde navn</h4>
            <p class="inline strong"><?php echo esc_attr( $fname ); ?> <?php echo esc_attr( $lname ); ?></p>
            
            
        </p>




        <p>
            <h4>Adresse</h4>
            <p class="inline strong"><?php echo esc_attr( $adresse ); ?></p>
            
        </p>
        <p>
            <h4>Postnummer / by</h4>
            <p class="inline strong"><?php echo esc_attr( $postalcode .' '. $city); ?></p>
            
        </p>

        <p>
            <h4>E-mail adresse</h4>
            <p class="inline strong"><?php echo esc_attr( $email ); ?></p>
            
        </p>

        <p>
            <h4>Telefon nummer</h4>
            <p class="inline strong"><?php echo esc_attr( $phonenumber ); ?></p>
            
        </p>

        <p>
            <h4>Klasse</h4>
            <p class="inline strong"><?php echo esc_attr( $klasse ); ?></p>
            
        </p>

        <p>
            <h4>Skole</h4>
            <p class="inline strong"><?php echo esc_attr( $skole ); ?></p>
            
        </p>

        
        <p>
            <h4>Alder</h4>
            <p class="inline strong"><?php echo $this->get_age($age); ?></p>
            
        </p>

        

        <p>
        <h4>Køn</h4>
        <?php echo $gender == 'man' ? 'Mand': 'Kvinde'; ?>
     
        </p>

        <h3>Forældres info</h3>

        <p>
            <h4>Forældres Fulde navn</h4>
            <p class="inline strong"><?php echo esc_attr( $pfname ); ?> <?php echo esc_attr( $plname ); ?></p>
            
            
        </p>

        <p>
            <h4>Forældres E-mail adresse</h4>
            <p class="inline strong"><?php echo esc_attr( $pemail ); ?></p>
            
        </p>

        <p>
            <h4>Forældres Telefon nummer</h4>
            <p class="inline strong"><?php echo esc_attr( $pphonenumber ); ?></p>
            
        </p>
<?php
    }

    public function render_meta_box($post)
    {



        wp_nonce_field( 'gestus_teen', 'gestus_teen_nonce' );

        $data = get_post_meta( $post->ID, '_gestus_teen_key', true );


        $user_ip = isset($data['user_ip']) ? $data['user_ip'] : '';


        $approved = isset($data['approved']) ? $data['approved'] : false;
        $featured = isset($data['featured']) ? $data['featured'] : false;
     



?>
        <div class="meta-container">
            <label class="inline strong left" for="gestus_teen_approved">Godkendt</label>
            <div class="right  inline">
                <div class="ui-toggle "><input type="checkbox" id="gestus_teen_approved" name="gestus_teen_approved" value="1" <?php echo $approved ? 'checked' : ''; ?>>
                    <label for="gestus_teen_approved"><div></div></label>
                </div>
            </div>
        </div>
        <div class="meta-container">
            <label class="inline strong left" for="gestus_teen_featured">Synlig</label>
            <div class="right  inline">
                <div class="ui-toggle "><input type="checkbox" id="gestus_teen_featured" name="gestus_teen_featured" value="1" <?php echo $featured ? 'checked' : ''; ?>>
                    <label for="gestus_teen_featured"><div></div></label>
                </div>
            </div>
        </div>
        <?php
    }

    public function save_meta_box($post_id)
    {

        //get values not ment to be editable directly from meta data. preventing dom manipulation of input fields
        $data = get_post_meta( $post_id, '_gestus_teen_key', true );

        $user_ip = isset($data['user_ip']) ? $data['user_ip'] : '';


        $fname = isset($data['fname']) ? $data['fname'] : '';
        $lname = isset($data['lname']) ? $data['lname'] : '';
        $email = isset($data['email']) ? $data['email'] : '';



        $adresse = isset($data['adresse']) ? $data['adresse'] : '';
        $postalcode = isset($data['postalcode']) ? $data['postalcode'] : '';
        $city = isset($data['city']) ? $data['city'] : '';
        $phonenumber = isset($data['phonenumber']) ? $data['phonenumber'] : '';
        $gender = isset($data['gender']) ? $data['gender']: '';
        $age = isset($data['age']) ? $data['age']: '';

        $klasse = isset($data['klasse']) ? $data['klasse'] : '';
        $skole = isset($data['skole']) ? $data['skole'] : '';

        $pfname = isset($data['pfname']) ? $data['pfname'] : '';
        $plname = isset($data['plname']) ? $data['plname'] : '';
        $pemail = isset($data['pemail']) ? $data['pemail'] : '';
        $pphonenumber = isset($data['pphonenumber']) ? $data['pphonenumber'] : '';

        $joinreason = isset($data['joinreason']) ? $data['joinreason'] : '';

        if (! isset($_POST['gestus_teen_nonce'])) {
            return $post_id;
        }

        $nonce = $_POST['gestus_teen_nonce'];
        if (! wp_verify_nonce( $nonce, 'gestus_teen' )) {
            return $post_id;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        if (! current_user_can( 'edit_post', $post_id ) ) {
            return $post_id;
        }



        $data = array(
            'fname' => $fname,
            'lname' => $lname,
            'email' => $email,
            'user_ip' =>  $user_ip,
            'adresse' => $adresse,
            'postalcode' => $postalcode,
            'city' => $city,
            'phonenumber' => $phonenumber,
            'gender' => $gender,
            'age' => $age,
            'skole' => $skole,
            'klasse' => $klasse,
            'pfname' => $pfname,
            'plname' => $plname,
            'pemail' => $pemail,
            'pphonenumber' => $pphonenumber,
            'joinreason' => $joinreason,



            'approved' => isset($_POST['gestus_teen_approved']) ? 1 : 0,
            'featured' => isset($_POST['gestus_teen_featured']) ? 1 : 0,
            
        );
        update_post_meta( $post_id, '_gestus_teen_key', $data );
    }


    public function sort_order_custom_data( $columns )
    {


        $title = $columns['title'];
        $date = $columns['date'];

        unset ( $columns['title'], $columns['date']);
     

        $columns['fname'] = 'Teens navn';
        $columns['phonenumber'] = 'Teens mobilnummer';
        $columns['email'] = 'Teens E-mail adresse';
        $columns['pfname'] = 'Forældre navn';
        $columns['pemail'] = 'Forældres E-mail';
        $columns['pphone'] = 'Forældres telefonnummer';
        $columns['gender'] = 'Køn';
        $columns['age'] = 'Alder';
        $columns['approved'] = 'Godkendt';
        //$columns['featured'] = 'Fremvises';
        $columns['date'] = $date;


        return $columns;
    }

    public function sort_order_custom_columns ($column, $post_id){

        $data = get_post_meta( $post_id, '_gestus_teen_key', true );

       
        $fname = isset($data['fname']) ? $data['fname'] : '';
        $lname = isset($data['lname']) ? $data['lname'] : '';
        $user_ip = isset($data['user_ip']) ? $data['user_ip'] : '';
        $email = isset($data['email']) ? $data['email'] : '';
        $gend = isset($data['gender']) ? $data['gender'] : '';
        $gender = ($gend == 'man') ? '<i class="fa fa-male fa-2x" style="color:#02A3FE"></i>' : '<i class="fa fa-female fa-2x" style="color:#FF007F"></i>';
        $age = isset($data['age']) ? $this->get_age($data['age']) : '' ;
        $phone = isset($data['phonenumber']) ? $data['phonenumber'] : '';
   
        $pfname = isset($data['pfname']) ? $data['pfname'] : '';
        
        $plname = isset($data['plname']) ? $data['plname'] : '';

        $pemail = isset($data['pemail']) ? $data['pemail'] : '';

        $pphone = isset($data['pphonenumber']) ? $data['pphonenumber'] : '';

      
        $approved = isset($data['approved']) && $data['approved'] === 1 ? '<i class="dashicons dashicons-thumbs-up" ></i>' : '<i class="dashicons dashicons-thumbs-down" ></i>';

        $featured = isset($data['featured']) && $data['featured'] === 1 ? '<i class="dashicons dashicons-thumbs-up" ></i>' : '<i class="dashicons dashicons-thumbs-down" ></i>';

        switch($column)
        {
            case 'fname':
                echo '<strong>' . $fname . ' ' .$lname . '</strong>';
            break;

            case 'approved':
                echo $approved;
            break;

            case 'gender':

                echo $gender ;

            break;

            case 'age':

                echo $age;

            break;

            case 'featured':
                echo $featured;
            break;

            case 'pfname':
                
                echo '<strong>' . $pfname . ' ' .$plname . '</strong>';

            break;

            case 'pemail':
                echo '<a href="mailto:'.$pemail.'">'.$pemail.'</a>';
            break;

            case 'email':
                echo '<a href="mailto:'.$email.'">'.$email.'</a>';
            break;

            case 'phonenumber':
                echo '<a href="tel:'.$phone.'">'.$phone.'</a>';
            break;

            
            case 'pphone':
                echo '<a href="tel:'.$pphone.'">'.$pphone.'</a>';
            break;
        }

    }

    public function sortable_order_rows ( $columns )
    {

        $columns['fname'] = 'fname';

        $columns['approved'] = 'approved';

        $columns['featured'] = 'featured';

        return $columns;
    }


}

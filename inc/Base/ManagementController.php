<?php


/**
 * 
 * @package gestus_nord
 */

namespace Inc\Base ;

use Inc\Api\SettingsApi;
use Inc\Base\BaseController;
use Inc\Api\Callbacks\ShortCodes;

class ManagementController extends BaseController
{

    public $settings;
    public $callbacks;

    public $meta_boxes = array();

  

    public $members;


    public function register()
    {
        $this->settings = new SettingsApi();
        $this->callbacks = new ShortCodes();




        
        if ( !$this->activated('management_manager')) return;



        add_action('init', (array( $this, 'gestus_management')));
        
        add_action('add_meta_boxes' , array( $this, 'add_metaboxes'));

        add_action('save_post' , array( $this, 'save_meta_box'));

        add_action('manage_management_posts_columns', array( $this , 'sort_order_custom_data'));

        add_action('manage_management_posts_custom_column', array( $this , 'sort_order_custom_columns'), 10 , 2);
       
        add_action('wp_enqueue_scripts', array($this, 'load_dashicons'), 999);


        add_filter('manage_edit_management_sortable_columns' , array( $this, 'sortable_order_rows'));

        $this->setShortcodePage();

        add_shortcode('management-members', array($this, 'management_members'));
    
       

    }


    function load_dashicons(){
        wp_enqueue_style('dashicons');
    }



    



    /**
     * 
     * define path to members
     */
        public function management_members()
    {

        ob_start();


        echo "<link rel=\"stylesheet\" href=\"$this->plugin_url/assets/management.css\" type=\"text/css\" media=\"all\" />";

    
        require_once("$this->plugin_path/templates/management/managements.php");   

        //echo "<script src=\"$this->plugin_url/assets/slider.js\"></script>";
    

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

                'parent_slug' => 'admin.php?page=gestus_nord_admin',
                'page_title' => 'shortcodes',
                'menu_title' => 'Shortcodes Bestyrrelse',
                'capability' => 'manage_options',
                'menu_slug' => 'gestus_management_shortcode',
                'callback' => array( $this->callbacks , 'Management_shortcodes')



            )
            );
            $this->settings->addSubPages( $subpage )->Register();
    }
     


    public function gestus_management()
    {


        $labels = array(

            'firstname' => 'managements',
            'singular_name' => 'management',
            'menu_name' => 'Bestyrrelse'
        );
        
        $args = array(

            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-groups',
            'exclude_from_search' => false,
            'show_ui' => true, 
            'show_in_menu' => 'gestus_nord_admin',
            'publicly_queryable' => false,
            'supports' => array( 'title' , 'editor' ,'thumbnail', 'page-attributes' ),
            'show_in_rest'      => true
            
        );

        
        register_post_type('bestyrrelse', $args);}


    
    /**
     * 
     */
        public function add_metaboxes(){

        add_meta_box(
            'gestus_management', //id
            'Tilføj / rediger medlem',//title
            array( $this, 'render_meta_box'),//callback
            'bestyrrelse', // screens / post types
            'side',// context
            'default'// priority
                // $args
                        
        );
        
    }

    public function render_meta_box($post)
    {

        $current_user = wp_get_current_user();

        wp_nonce_field( 'gestus_management', 'gestus_management_nonce' );

        $data = get_post_meta( $post->ID, '_gestus_management_key', true );



        $user_ip = isset($data['user_ip']) ? $data['user_ip'] : '';
        $firstname = isset($data['firstname']) ? $data['firstname'] : '';
        $lastname = isset($data['lastname']) ? $data['lastname'] : '';
        $company = isset($data['company']) ? $data['company'] : '';
        $position = isset($data['position']) ? $data['position'] : '';
        $email = isset($data['email']) ? $data['email'] : '';
        $phone = isset($data['phone']) ? $data['phone'] : '';
        $approved = isset($data['approved']) ? $data['approved'] : false;
        $featured = isset($data['featured']) ? $data['featured'] : false;
        ?>
        <p>
            
            <p class="inline strong"><input type="hidden" name="user_ip" id="user_ip" value="<?php echo $this->ip ?>"></p>
            
        </p>
        <p>
            <label class="meta-label" for="gestus_management_firstname">Medlems fornavn</label>
            <p class="inline strong"><input type="text" value="<?php echo esc_attr( $firstname ); ?>" name="gestus_management_firstname" id="gestus_management_firstname" ></p>
            
            
        </p>

        <p>
            <label class="meta-label" for="gestus_management_lastname">Medlems efternavn</label>
            <p class="inline strong"><input type="text" value="<?php echo esc_attr( $lastname ); ?>" name="gestus_management_lastname" id="gestus_management_lastname" ></p>
            
            
        </p>
        <p>
            <label class="meta-label" for="gestus_management_phone">Kontakt Telefon nummer</label>
            <p class="inline strong"><input type="text"  id="gestus_management_phone" name="gestus_management_phone" value="<?php echo esc_attr( $phone ); ?>"></p>
            
        </p>
        <p>
            <label class="meta-label" for="gestus_management_email">Kontakt E-mail</label>
            <p class="inline strong"><input type="text"  id="gestus_management_email" name="gestus_management_email" value="<?php echo esc_attr( $email ); ?>"></p>
            
        </p>

        <p>
            <label class="meta-label" for="gestus_management_company">Firma</label>
            <p class="inline strong"><input type="text"  id="gestus_management_company" name="gestus_management_company" value="<?php echo esc_attr( $company ); ?>"></p>
            
        </p>

        <p>
            <label class="meta-label" for="gestus_management_position">Bestyrrelses postition</label>
            <p class="inline strong">
            <select name="gestus_management_position" id="gestus_management_position" required="required">
            
            <?php echo $this->membertypes;?>
            
        
            <!-- array of contents found in base controller-->
            <?php foreach($this->membertypes as $type => $value) {  ?>
                <option value="<?php echo $type?>" <?php selected( $position, $type ); ?>><?php echo $value?></option>
                 <?php }?>
         
            </select>
            </p>
            
        </p>
        <div class="meta-container">
            <label class="inline strong left" for="gestus_management_approved">Aktivt medlem</label>
            <div class="right  inline">
                <div class="ui-toggle "><input type="checkbox" id="gestus_management_approved" name="gestus_management_approved" value="1" <?php echo $approved ? 'checked' : ''; ?>>
                    <label for="gestus_management_approved"><div></div></label>
                </div>
            </div>
        </div>
        <div class="meta-container">
            <label class="inline strong left" for="gestus_management_featured">Synlig på siden</label>
            <div class="right  inline">
                <div class="ui-toggle "><input type="checkbox" id="gestus_management_featured" name="gestus_management_featured" value="1" <?php echo $featured ? 'checked' : ''; ?>>
                    <label for="gestus_management_featured"><div></div></label>
                </div>
            </div>
        </div>
        <?php
    }

    public function save_meta_box($post_id)
    {

        //get values not ment to be editable directly from meta data. preventing dom manipulation of input fields
        $data = get_post_meta( $post_id, '_gestus_management_key', true );

        $user_ip = isset($data['user_ip']) ? $data['user_ip'] : $this->ip;
        $firstname = isset($_POST['gestus_management_firstname']) ? $_POST['gestus_management_firstname']: '';
        $lastname = isset($_POST['gestus_management_lastname']) ? $_POST['gestus_management_lastname']: '';
        $email = isset($_POST['gestus_management_email']) ? $_POST['gestus_management_email']: '';
        $phone = isset($_POST['gestus_management_phone']) ? $_POST['gestus_management_phone']: '';
        $company = isset($_POST['gestus_management_company']) ? $_POST['gestus_management_company']: '';
        $position = isset($_POST['gestus_management_position']) ? $_POST['gestus_management_position'] : '';
       

        if (! isset($_POST['gestus_management_nonce'])) {
            return $post_id;
        }

        $nonce = $_POST['gestus_management_nonce'];
        if (! wp_verify_nonce( $nonce, 'gestus_management' )) {
            return $post_id;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        if (! current_user_can( 'edit_post', $post_id ) ) {
            return $post_id;
        }

        $data = array(
            'firstname' => $firstname,
            'lastname' => $lastname,
            'user_ip' =>  $user_ip,
            'email' => $email,
            'phone' => $phone,
            'company' => $company,
            'position' => $position,
            'approved' => isset($_POST['gestus_management_approved']) ? 1 : 0,
            'featured' => isset($_POST['gestus_management_featured']) ? 1 : 0,
            
        );
        update_post_meta( $post_id, '_gestus_management_key', $data );
    }


    public function sort_order_custom_data( $columns )
    {

       

        $title = $columns['title'];
        $date = $columns['date'];

        unset ( $columns['title'], $columns['date']);
     
       // $columns['title'] = $title;
        $columns['member'] = 'Navn';
        $columns['phone'] = 'Telefonnummer';
        $columns['email'] = 'E-mail addresse';
        $columns['approved'] = 'Godkendt';
        $columns['featured'] = 'Fremvises';
        $columns['date'] = $date;


        return $columns;
    }

    public function sort_order_custom_columns ($column, $post_id){


        
        $data = get_post_meta( $post_id, '_gestus_management_key', true );

       
        $firstname = isset($data['firstname']) ? $data['firstname'] : '';
        $lastname = isset($data['lastname']) ? $data['lastname'] : '';
        $position = isset($data['position']) ? $data['position'] : '';
        $email = isset($data['email']) ? $data['email'] : '';
        $phone = isset($data['phone']) ? $data['phone'] : '';
        $user_ip = isset($data['user_ip']) ? $data['user_ip'] : '';
        $email = isset($data['email']) ? $data['email'] : '';

      
        $approved = isset($data['approved']) && $data['approved'] === 1 ? '<i class="dashicons dashicons-thumbs-up" ></i>' : '<i class="dashicons dashicons-thumbs-down" ></i>';
        $featured = isset($data['featured']) && $data['featured'] === 1 ? '<i class="dashicons dashicons-thumbs-up" ></i>' : '<i class="dashicons dashicons-thumbs-down" ></i>';

        switch($column)
        {
            case 'member':
                
                echo '<strong>' . $firstname . ' - ' . $lastname . ' - ' .$position. '</strong>';
            break;

            case 'email':
                echo '<a href="mailto:'.$email.'">'.$email.'</a>';
            break;

            case 'phone':
                echo '<a href="tel:'.$phone.'">'.$phone.'</a>';
            break;

            case 'firstname':
                echo '<strong>' . $firstname . '</strong><br>'. $user_ip;
            break;

            case 'approved':
                echo $approved;
            break;

            case 'featured':
                echo $featured;
            break;
        }

    }

    public function sortable_order_rows ( $columns )
    {

        $columns['firstname'] = 'firstname';

        $columns['approved'] = 'approved';

        $columns['featured'] = 'featured';

        return $columns;
    }



}

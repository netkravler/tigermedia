<?php


/**
 * 
 * @package gestus_nord
 */

namespace Inc\Base ;

use Inc\Api\SettingsApi;
use Inc\Base\BaseController;


class AidController extends BaseController
{
        

    public $subpages = array();

    
    public $post_status;


    
    public $contacts = array();

    public $kids = array();

    public $parents = array();





    public function register()
    {

        
        if ( !$this->activated('aid_manager')) return;

        $this->settings = new SettingsApi();







        add_action('add_meta_boxes' , array( $this, 'add_metaboxes'));

        add_action('save_post' , array( $this, 'save_meta_box'));



        
        add_action('init', (array( $this, 'gestus_aid')));

    
        
        $this->settings->addSubPages( $this->subpages )->register();


        add_action('manage_gestus_aid_posts_columns', array( $this , 'sort_order_custom_data'));

        add_action('manage_gestus_aid_posts_custom_column', array( $this , 'sort_order_custom_columns'), 10 , 2);
 
        add_filter('manage_edit_gestus_aid_sortable_columns' , array( $this, 'sortable_order_rows'));


    }



   
   

/**
 * 
 * 
 * register post type
 */
    public function gestus_aid()
    {


        $labels = array(

            'name' => 'Gestus Aid',
            'singular_name' => 'gestus aid',
            'menu_name' => 'Typer af hjælp'
        );
        
        $args = array(

            'labels' => $labels,
            'public' => true,
            'show_ui' => true, 
            'has_archive' => true,
            'menu_icon' => 'dashicons-testimonial',
            'exclude_from_search' => false,
            'show_in_menu' => 'gestus_nord_admin',
            'publicly_queryable' => true,
            'rewrite'            => array( 'slug' => 'gestus_hjaelp' ),
            'supports' => array(
                'page-attributes',
                'title',
                'editor',
                'author',
                'excerpt',
                'categories',
                'thumbnail',
                'comments' ),
		        'show_in_rest' => true,
            //'capabilities' => array('create_posts' => false)
            
        );

        
        register_post_type('gestus_aid', $args);


        }




     /**
     * register metaboxes
     */
    public function add_metaboxes(){

        add_meta_box(
            'gestus_aid', //id
            'Gestus hjælp',//title
            array( $this, 'render_meta_box'),//callback
            'gestus_aid', // screens / post types
            'side',// context
            'default'// priority
                // $args
                        
        );
        
    }

    /**
     * 
     * 
     * render metaboxes
     */

    public function render_meta_box($post)
    {




        global $current_user; wp_get_current_user();

        wp_nonce_field( 'gestus_aid', 'gestus_aid_nonce' );

        $data = get_post_meta( $post->ID, '_gestus_aid_key', true );
        
        $user_ip = isset($data['user_ip']) ? $data['user_ip'] : $this->ip;
        $user = isset($data['gestus_aid_user']) ? $data['gestus_aid_user'] : $current_user->display_name;
        $user_email = isset($data['gestus_user_email']) ? $data['gestus_user_email'] : $current_user->user_email;
        $extra_kids = isset($data['post_extra_kids']) ? $data['post_extra_kids'] : false;

        $start_width_one_kid = isset($data['start_width_one_kid']) ? $data['start_width_one_kid'] : false;

        $only_one_kid = isset($data['only_one_kid']) ? $data['only_one_kid'] : false;

        $kids_total = isset($data['kids_total']) ? $data['kids_total'] : false;

        $extra_contacts = isset($data['post_extra_contact']) ? $data['post_extra_contact'] : false;
        $show_spouse = isset($data['show_spouse']) ? $data['show_spouse'] : false;
        $show_roomie = isset($data['show_roomie']) ? $data['show_roomie'] : false;
        $aid_comment = isset($data['aid_comment']) ? $data['aid_comment'] : false;
        $aid_info = isset($data['aid_info']) ? $data['aid_info'] : false;
        $activate_aid_type = isset($data['activate_aid_type']) ? $data['activate_aid_type'] : false;

        $activate_konfirmation = isset($data['activate_konfirmation']) ? $data['activate_konfirmation'] : false;

        $active_time = isset($data['active_time']) ? $data['active_time'] : false;
        $aid_start = isset($data['start_time']) ? $data['start_time'] : '';
        $aid_end = isset($data['end_time']) ? $data['end_time'] : '';

        $email_from = isset($data['email_from']) ? $data['email_from'] : '';


        if(isset($data['activate_aid_type']) && $data['activate_aid_type'] == 1){

            $this->change_post_status($post->ID , 'publish');
 
         }else {
 
             $this->change_post_status($post->ID , 'pending');
 
         }
        
         ?>

         

        
            <label class="meta-label" for="gestus_testimonial_ip">Bruger IP</label>
            <p class="inline strong"><?php echo $this->ip ?></p>
            <input type="hidden" name="user_ip" id="user_ip" value="<?php echo  $this->ip ; ?>">
            
        
        <p>
            <label class="meta-label" for="gestus_aid_user">Oprettet / redigeret af</label>
        
            <input type="text" name="gestus_aid_user" id="gestus_aid_user" value="<?php echo esc_attr( $user ); ?>" readonly>
            
        </p>
        <p>
            <label class="meta-label" for="gestus_user_email">Bruger E-mail på opretteren</label>
            <input type="text" name="gestus_user_email" id="gestus_user_email" value="<?php echo esc_attr( $user_email ); ?>" readonly>
        </p>

        <!--
        Settings for toggle on of using kids in frontend form
        -->
        <div class="meta-container">
            <label class="inline strong left" for="post_extra_kids">Brug tilføj børn på siden</label>
            <div class="right  inline">
                <div class="ui-toggle "><input type="checkbox" id="post_extra_kids" name="post_extra_kids" value="1" <?php echo  $extra_kids ? 'checked' : ''; ?>>
                    <label for="post_extra_kids"><div></div></label>
                </div>
            </div>
        </div>

                <!--
        Settings for toggle on of using kids in frontend form
        -->
        <div class="meta-container">
            <label class="inline strong left" for="start_width_one_kid">Start med mindst et barn</label>
            <div class="right  inline">
                <div class="ui-toggle "><input type="checkbox" id="start_width_one_kid" name="start_width_one_kid" value="1" <?php echo  $start_width_one_kid  ? 'checked' : ''; ?>>
                    <label for="start_width_one_kid"><div></div></label>
                </div>
            </div>
        </div>

       <!-- Settings for toggle on of using kids in frontend form
        -->
        <div class="meta-container">
            <label class="inline strong left" for="kids_total">Vis angiv antal børn</label>
            <div class="right  inline">
                <div class="ui-toggle "><input type="checkbox" id="kids_total" name="kids_total" value="1" <?php echo  $kids_total  ? 'checked' : ''; ?>>
                    <label for="kids_total"><div></div></label>
                </div>
            </div>
        </div>


                <!--
        Settings for toggle on of using kids in frontend form
        -->
        <div class="meta-container">
            <label class="inline strong left" for="only_one_kid">Accepter kun 1 barn</label>
            <div class="right  inline">
                <div class="ui-toggle "><input type="checkbox" id="only_one_kid" name="only_one_kid" value="1" <?php echo  $only_one_kid  ? 'checked' : ''; ?>>
                    <label for="only_one_kid"><div></div></label>
                </div>
            </div>
        </div>

        <!--
        Settings for toggle on of using contacts in frontend form
        -->
        <div class="meta-container">
            <label class="inline strong left" for="post_extra_contact">Brug kontakter på siden</label>
            <div class="right  inline">
                <div class="ui-toggle "><input type="checkbox" id="post_extra_contact" name="post_extra_contact" value="1" <?php echo $extra_contacts ? 'checked' : ''; ?>>
                    <label for="post_extra_contact"><div></div></label>
                </div>
            </div>
        </div>

        <!--
        Settings for toggle on of show spouse in frontend form
        -->
        <div class="meta-container">
            <label class="inline strong left" for="show_spouse">Vis ægtefælle på siden</label>
            <div class="right  inline">
                <div class="ui-toggle "><input type="checkbox" id="show_spouse" name="show_spouse" value="1" <?php echo $show_spouse ? 'checked' : ''; ?>>
                    <label for="show_spouse"><div></div></label>
                </div>
            </div>
        </div>

                <!--
        Settings for toggle on of show roomie in frontend form
        -->
        <div class="meta-container">
            <label class="inline strong left" for="show_roomie">Vis samlever på siden</label>
            <div class="right  inline">
                <div class="ui-toggle "><input type="checkbox" id="show_roomie" name="show_roomie" value="1" <?php echo $show_roomie ? 'checked' : ''; ?>>
                    <label for="show_roomie"><div></div></label>
                </div>
            </div>
        </div>



        <div class="meta-container">
            <label class="inline strong left" for="aid_comment">Vis kommentarfelt på siden</label>
            <div class="right  inline">
                <div class="ui-toggle "><input type="checkbox" id="aid_comment" name="aid_comment" value="1" <?php echo $aid_comment ? 'checked' : ''; ?>>
                    <label for="aid_comment"><div></div></label>
                </div>
            </div>
        </div>
                        <!--
        Settings for toggle on of show show_on_bhalf_off in frontend form
        -->
        <div class="meta-container">
            <label class="inline strong left" for="activate_aid_type">Aktiver denne type hjælp</label>
            <div class="right  inline">

                <div class="ui-toggle "><input type="checkbox" id="activate_aid_type" name="activate_aid_type" value="1" <?php echo $activate_aid_type ? 'checked' : ''; ?> >
                    <label for="activate_aid_type"><div></div></label>
                </div>
            </div>
        </div>

                                <!--
        Settings for toggle on of show show_on_bhalf_off in frontend form
        -->
        <div class="meta-container">
            <label class="inline strong left" for="activate_konfirmation">Aktiver konfirmation hjælp</label>
            <div class="right  inline">

                <div class="ui-toggle "><input type="checkbox" id="activate_konfirmation" name="activate_konfirmation" value="1" <?php echo $activate_konfirmation ? 'checked' : ''; ?> >
                    <label for="activate_konfirmation"><div></div></label>
                </div>
            </div>
        </div>

                                <!--
        Settings for toggle on of show show_on_bhalf_off in frontend form
        -->
        <div class="meta-container">
            <label class="inline strong left" for="active_time">Aktiver ansøgnings løbetid</label>
            <div class="right  inline">

                <div class="ui-toggle "><input type="checkbox" id="active_time" name="active_time" value="1" <?php echo $active_time ? 'checked' : ''; ?> onchange="visible_hide(this.id ,  new Array('start_time', 'end_time') , 'duration')">
                    <label for="active_time"><div></div></label>
                </div>
            </div>
        </div>




        <div id="duration" style="display: <?php echo $active_time ? 'block' : 'none'; ?>">
        <p>
            <label class="meta-label" for="start_time">Ansøgning frist start</label>
            <input type="datetime-local" name="<?php echo $active_time ? 'start_time' : 'dfgdgvvdcfgd'; ?>" id="start_time" value="<?php echo esc_attr( $aid_start ); ?>" >
        </p>

        <p>
            <label class="meta-label" for="end_time">Ansøgning frist slut </label>
            <input type="datetime-local" name="<?php echo $active_time ? 'end_time' : 'vgdgsdgdgd'; ?>" id="end_time" value="<?php echo esc_attr( $aid_end ); ?>" >
        </p>
        </div>
        <div class="meta-container" >
            <label class="inline strong left" for="email_from">Set afsender email </label><br>
            <div >
                <div ><input type="email" id="email_from" required name="email_from" value="<?php echo $email_from; ?>""  >
                    <label for="email_from">
                        <div></div>
                    </label>
                </div>
            </div>
        </div><br>
        <div >
        <div >
            <label class="meta-label" for="aid_info">Skriv evt. Info om <?php echo get_the_title();?></label>

            <textarea  name="aid_info" id="aid_info" rows="6" style="width: 100%; display:inline-block"><?php echo esc_attr( $aid_info ); ?></textarea>
        </div>
        </div>

        <?php
    }


    /**
     * 
     * 
     * save metaboxes
     */
    public function save_meta_box($post_id)


    {

       // ;


        global $current_user; wp_get_current_user();

        $data = get_post_meta( $post_id, '_gestus_aid_key', true );
        
        $user_ip = isset($data['user_ip']) ? $data['user_ip'] : $this->ip;
        $user = isset($data['gestus_aid_user']) ? $data['gestus_aid_user'] :  $current_user->display_name;
        $user_email = isset($data['user_email']) ? $data['user_email'] : '';



        if (! isset($_POST['gestus_aid_nonce'])) {
            return $post_id;
        }

        $nonce = $_POST['gestus_aid_nonce'];
        if (! wp_verify_nonce( $nonce, 'gestus_aid' )) {
            return $post_id;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        if (! current_user_can( 'edit_post', $post_id ) ) {
            return $post_id;
        }

        $data = array(
            'gestus_aid_user' => $user,
            'user_ip' =>  $user_ip,
            'user_email' => $user_email,

            'post_extra_kids' => isset($_POST['post_extra_kids']) ? 1 : 0,
            'start_width_one_kid' => isset($_POST['start_width_one_kid']) ? 1 : 0,
            'only_one_kid' => isset($_POST['only_one_kid']) ? 1 : 0,
            'kids_total' => isset($_POST['kids_total']) ? 1 : 0,
            'post_extra_contact' => isset($_POST['post_extra_contact']) ? 1 : 0,
            'show_spouse' => isset($_POST['show_spouse']) ? 1 : 0,
            'show_roomie' => isset($_POST['show_roomie']) ? 1 : 0,
            'aid_comment' => isset($_POST['aid_comment']) ? 1 : 0,
            'aid_info' => isset($_POST['aid_info']) ? $_POST['aid_info'] : '',
            
            'activate_aid_type' => isset($_POST['activate_aid_type']) ? 1 : 0,

            'activate_konfirmation' => isset($_POST['activate_konfirmation']) ? 1 : 0,

            'active_time' => isset($_POST['active_time']) ? $_POST['active_time'] : '',
            'start_time' => isset($_POST['start_time']) ? $_POST['start_time'] : '',
            'end_time' => isset($_POST['end_time']) ? $_POST['end_time'] : '',

            'email_from' => isset($_POST['email_from']) ? $_POST['email_from'] : '',
            'aid_id' => get_the_ID()
        );
        update_post_meta( $post_id, '_gestus_aid_key', $data );

        
    }


    public function sort_order_custom_data( $columns )
    {



        $title = $columns['title'];
        $date = $columns['date'];

        $author = $columns['author'];

        unset ( $columns['title'], $columns['date'], $columns['author'], $columns['comments']);
     
        $columns['title'] = $title;

        $columns['starts'] = 'Start dato';

        $columns['ends'] = 'Slut dato';

        $columns['is_active'] = 'Er aktiv';

        $columns['date'] = $date;


        return $columns;
    }

    public function sort_order_custom_columns ($column, $post_id){

       

                //get the type of aid this post is targeted
        $post_page_tag = get_post_meta( $post_id, '_aidtype', true );

        $data = get_post_meta( $post_id, '_gestus_aid_key', true );

        $starts = isset($data['start_time']) && $data['active_time'] == 1 ? $data['start_time'] : '';

        $ends = isset($data['end_time']) && $data['active_time'] == 1 ? $data['end_time'] : '';

        $active = isset($data['activate_aid_type']) && $data['activate_aid_type'] === 1 ? '<i class="dashicons dashicons-thumbs-up" ></i>' : '<i class="dashicons dashicons-thumbs-down" ></i>';

        switch($column)
        {


            case 'starts':

                 if($starts){ echo $this->reformat_time($starts);};

            break;



            case 'ends':

                if($ends){ echo $this->reformat_time($ends);};

            break;


            case 'is_active':

                echo $active;

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

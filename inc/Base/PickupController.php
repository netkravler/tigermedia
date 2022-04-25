<?php

namespace Inc\Base;

use Inc\Base\BaseController;

class PickupController extends BaseController
{

    public $posttype;

    public function register()
    {


        $this->posttype = 'pickup';

        add_action('init', array($this, 'gestus_pickup') );
        add_action('add_meta_boxes' , array( $this, 'add_metaboxes'));
        add_action('save_post' , array( $this, 'save_meta_box'));

        add_action('manage_pickup_posts_columns', array( $this , 'sort_order_custom_data'));
        add_action('manage_pickup_posts_custom_column', array( $this , 'sort_order_custom_columns'), 10 , 2);
       



        add_filter('manage_edit_pickup_sortable_columns' , array( $this, 'sortable_order_rows'));

    }

    public function gestus_pickup()
    {

        
        $labels = array(

            'name' => 'pickup',
            'singular_name' => $this->posttype,
            'menu_name' => 'Afhentning - mødesteder'
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
            'capabilities' => array('create_posts' => true),
            'map_meta_cap' => true
            
        );


        
        register_post_type($this->posttype, $args);
    
    }



    /**
     * 
     */
    public function add_metaboxes(){

        add_meta_box(
            'gestus_pickup', //id
            'Afhentning - mødesteder',//title
            array( $this, 'render_meta_box'),//callback
            'pickup', // screens / post types
            'side',// context
            'default'// priority
                // $args
                        
        );
        
    }

    public function render_meta_box($post)
    {



        wp_nonce_field( 'gestus_pickup', 'gestus_pickup_nonce' );

        $data = get_post_meta( $post->ID, '_gestus_pickup_key', true );


        $aid_args = array(

            'post_type' => 'gestus_aid',
            'posts_per_page' => -1,
            'fields'=> 'ids',
            'post_status' => 'any'
            );
            
        $aid_ids = get_posts($aid_args);


        ?>

            <select name="pickup" id="pickup">
            <option value="">Vælg type af hjælp</option>
            <?php foreach($aid_ids as $ids => $value){ 

                $selected = $data == $value ? 'selected' : '';
 

                ?>

                <option value="<?php echo $value?>" <?php echo $selected ?>> <?php echo get_the_title($value); ?></option>

                <?php }?>
            </select>

        <?php

    }

    public function save_meta_box($post_id)
    {

        //get values not ment to be editable directly from meta data. preventing dom manipulation of input fields
        $data = get_post_meta( $post_id, '_gestus_pickup_key', true );

        //$company_ip = isset($data['comany_ip']) ? $data['comany_ip'] : $this->ip;

        $pickup = isset($_POST['pickup']) ? $_POST['pickup'] : '';
        
        if (! isset($_POST['gestus_pickup_nonce'])) {
            return $post_id;
        }

        $nonce = $_POST['gestus_pickup_nonce'];
        if (! wp_verify_nonce( $nonce, 'gestus_pickup' )) {
            return $post_id;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        if (! current_user_can( 'edit_post', $post_id ) ) {
            return $post_id;
        }


        update_post_meta( $post_id, '_gestus_pickup_key', $pickup );

    }
    

    public function sort_order_custom_data( $columns )
    {

        $date = $columns['date'];

        $title = $columns['title'];

        unset($columns['date']);

        $columns['locale'] = 'Gælder type hjælp';
       
        $columns['date'] = $date;


        return $columns;
    }



    public function sort_order_custom_columns ($column, $post_id){

        $data = get_post_meta( $post_id, '_gestus_pickup_key', true );

       

        switch($column)
        {
            case 'locale':

                echo get_the_title($data);

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

<?php


/**
 * 
 * @package gestus_nord
 */

namespace Inc\Base ;

use Inc\Api\SettingsApi;

use Inc\Base\BaseController;
use Inc\Api\Callbacks\AdminCallbacks;
use Inc\Api\Callbacks\ShortCodes;


class VolunteerController extends BaseController
{
    public $settings;



    public $callbacks;

    public $meta_boxes = array();

    public $posttype;



    public function register()
    {
        if ( !$this->activated('volunteer_manager')) return;

        $this->settings = new SettingsApi();
        
        $this->callbacks = new AdminCallbacks();

        $this->voluncallbacks = new ShortCodes();

        $this->posttype = 'volunteer';

        







        add_action('init', (array( $this, 'gestus_volunteer')));
        
        add_action('add_meta_boxes' , array( $this, 'add_metaboxes'));

        add_action('save_post' , array( $this, 'save_meta_box'));

        add_action('manage_volunteer_posts_columns', array( $this , 'sort_order_custom_data'));

        add_action('manage_volunteer_posts_custom_column', array( $this , 'sort_order_custom_columns'), 10 , 2);
       
        add_action('wp_enqueue_scripts', array($this, 'load_dashicons'), 999);


        add_filter('manage_edit_volunteer_sortable_columns' , array( $this, 'sortable_order_rows'));

        $this->setShortcodePage();

      
    
         

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

                'parent_slug' => 'edit.php?post_type=volunteer',
                'page_title' => 'shortcodes',
                'menu_title' => 'Shortcodes',
                'capability' => 'manage_options',
                'menu_slug' => 'gestus_volunteer_shortcode',
                'callback' => array( $this->voluncallbacks , 'volun_shortcodes')



            )
            );
            $this->settings->addSubPages( $subpage )->Register();
    }
     


    public function gestus_volunteer()
    {


        $labels = array(

            'firstname' => 'volunteers',
            'singular_name' => 'volunteer',
            'menu_name' => 'Frivillige'
        );
        
        $args = array(

            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'show_ui' => true, 
            'menu_icon' => 'dashicons-groups',
            'exclude_from_search' => false,
            'show_in_menu' => 'gestus_nord_admin',
            'publicly_queryable' => true,
            'supports' => array( 'title' , 'editor' ,'thumbnail' ),
            'show_in_rest'      => true
            
        );

        
        register_post_type($this->posttype, $args);}


    
    /**
     * 
     */
        public function add_metaboxes(){

        add_meta_box(
            'gestus_volunteer', //id
            'volunteers',//title
            array( $this, 'render_meta_box'),//callback
            'volunteer', // screens / post types
            'side',// context
            'default'// priority
                // $args
                        
        );
        
    }

    public function render_meta_box($post)
    {

        $current_user = wp_get_current_user();

        wp_nonce_field( 'gestus_volunteer', 'gestus_volunteer_nonce' );

        $data = get_post_meta( $post->ID, '_gestus_volunteer_key', true );

        $output = get_option('gestus_settings_manager');

        $user_ip = isset($data['user_ip']) ? $data['user_ip'] : '';
  
        $name = isset($data['name']) ? $data['name']: '';
        $adresse = isset($data['adresse']) ? $data['adresse']: '';
        $city = isset($data['city']) ? $data['city']: '';
        $gender = isset($data['gender']) ? $data['gender']: '';
        $birthday = isset($data['age']) ? $data['age']: '';

        $email = isset($data['email']) ? $data['email']: '';
        $postalcode = isset($data['postalcode']) ? $data['postalcode']: '';
        $phonenumber = isset($data['phonenumber']) ? $data['phonenumber']: '';

        $attest = isset($data['attest']) ? $data['attest']: '';

        $noattest = isset($data['noattest']) ? $data['noattest']: '';

        

        $approved = isset($data['approved']) ? $data['approved'] : false;
        $featured = isset($data['featured']) ? $data['featured'] : false;
        ?>
        <p>
            
        <label class="meta-label" for="user_ip">Ansøgning oprettet på ip adresse;  </label>
              
            <input type="text" name="user_ip" id="user_ip" value="<?php echo $user_ip ?>" readonly>
            
        </p>

        <p>
            <label class="meta-label" for="gestus_volunteer_name">Frivlligs navn</label>
            <p class="inline strong"><input type="text" value="<?php echo esc_attr( $name ); ?>" name="gestus_volunteer_name" id="gestus_volunteer_name" ></p>
            
            
        </p>
        <label class="meta-label" for="gestus_volunteer_adresse">Frivilliges adresse</label>
            <p class="inline strong"><input type="text"  id="gestus_volunteer_adresse" name="gestus_volunteer_adresse" value="<?php echo esc_attr( $adresse ); ?>"></p>
            
        </p>
        <p>
            <label class="meta-label" for="gestus_volunteer_postalcode">Postnummer</label>
            <p class="inline strong"><input type="text"  id="gestus_volunteer_postalcode" name="gestus_volunteer_postalcode" value="<?php echo esc_attr( $postalcode ); ?>"></p>
            
        </p>
        <p>
            <label class="meta-label" for="gestus_volunteer_city">Frivilliges by</label>
            <p class="inline strong"><input type="text"  id="gestus_volunteer_city" name="gestus_volunteer_city" value="<?php echo esc_attr( $city ); ?>"></p>
            
        </p>

        <p>
            <label class="meta-label" for="gestus_volunteer_phonenumber">Frivvliges Telefonnummer</label>
            <p class="inline strong"><input type="text"  id="gestus_volunteer_phonenumber" name="gestus_volunteer_phonenumber" value="<?php echo esc_attr( $phonenumber ); ?>"></p>
            
        </p>
        <p>
            <label class="meta-label" for="gestus_volunteer_email">Frivlliges E-mail</label>
            <p class="inline strong"><input type="text"  id="gestus_volunteer_email" name="gestus_volunteer_email" value="<?php echo esc_attr( $email ); ?>"></p>
            
        </p>

        <p>
            <label class="meta-label" for="gestus_volunteer_birthday">Frivlliges Fødseldag / alder</label>
            <p class="inline strong"><input type="date"  id="gestus_volunteer_birthday" name="gestus_volunteer_birthday" value="<?php echo esc_attr( $birthday ); ?>"></p>
            <p><?php echo $this->get_age($birthday)?></p>
        </p>

        <p>

            <label for="gender_man">Mand<input type="radio" id="gender_man" name="gestus_volunteer_gender" value="man" class="field-input" data-type="checkbox" required="required"  <?php echo $gender == 'man' ? 'checked' : '' ;?>></label>
            <label for="gender_woman">Kvinde<input type="radio" id="gender_woman" name="gestus_volunteer_gender" value="woman" class="field-input" data-type="checkbox" <?php echo $gender == 'woman' ? 'checked' : '' ;?>></label>
    
        </p>

     
          
<div class="field-container ">

<h6>Hvilke aktiviteter ønsker du at hjælpe i ?</h6>

<fieldset class="activities">
  <legend>Kræver børneattest</legend>  
<?php


foreach($output as $item => $key){


if (isset($key['attest'])){?>

<div class="meta-container">
            <label class="inline strong left" for="<?php echo $item?>"><?php echo $item ?></label>
            <div class="right  inline">
                <div class="ui-toggle "><input type="checkbox" id="<?php echo $item?>" name="attest[]"  <?php echo $this->if_in_string($attest , $item) ? 'checked': '';?> value="<?php echo $item?>">
                    <label for="<?php echo $item?>"><div></div></label>
                </div>
            </div>
        </div>

       
<?php
}

}?></div>
<small class="field-msg_wrong error" data-error="wrongformatAttest">Fødselsdato er påkrævet</small>
</fieldset>


<div class="field-container ">
<fieldset class="activities">
<legend>Kræver <u>ikke</u> børneattest</legend>
<?php


foreach($output as $item => $key){


  if (!isset($key['attest'])){?>

<div class="meta-container">
            <label class="inline strong left" for="<?php echo $item?>"><?php echo $item ?></label>
            <div class="right  inline">
                <div class="ui-toggle "><input type="checkbox" id="<?php echo $item?>" name="noattest[]" <?php echo $this->if_in_string($noattest , $item) ? 'checked': '';?>  value="<?php echo $item?>">
                    <label for="<?php echo $item?>"><div></div></label>
                </div>
            </div>
        </div>



 <?php }
}?>
</div>
<small class="field-msg_wrong error" data-error="wrongformatNoattest">Fødselsdato er påkrævet</small>
</fieldset>




        <div class="meta-container">
            <label class="inline strong left" for="gestus_volunteer_approved">Aktivt medlem</label>
            <div class="right  inline">
                <div class="ui-toggle "><input type="checkbox" id="gestus_volunteer_approved" name="gestus_volunteer_approved" value="1" <?php echo $approved ? 'checked' : ''; ?>>
                    <label for="gestus_volunteer_approved"><div></div></label>
                </div>
            </div>
        </div>
        <div class="meta-container">
            <label class="inline strong left" for="gestus_volunteer_featured">Synlig på siden</label>
            <div class="right  inline">
                <div class="ui-toggle "><input type="checkbox" id="gestus_volunteer_featured" name="gestus_volunteer_featured" value="1" <?php echo $featured ? 'checked' : ''; ?>>
                    <label for="gestus_volunteer_featured"><div></div></label>
                </div>
            </div>
        </div>
        <?php
    }

    public function save_meta_box($post_id)
    {

        //get values not ment to be editable directly from meta data. preventing dom manipulation of input fields
        $data = get_post_meta( $post_id, '_gestus_volunteer_key', true );

        $user_ip = isset($data['user_ip']) ? $data['user_ip'] : '';
  
        $name = isset($_POST['gestus_volunteer_name']) ? $_POST['gestus_volunteer_name']: '';
        $adresse = isset($_POST['gestus_volunteer_adresse']) ? $_POST['gestus_volunteer_adresse']: '';
        $postalcode = isset($_POST['gestus_volunteer_postalcode']) ? $_POST['gestus_volunteer_postalcode']: '';
        $city = isset($_POST['gestus_volunteer_city']) ? $_POST['gestus_volunteer_city']: '';
        $phonenumber = isset($_POST['gestus_volunteer_phonenumber']) ? $_POST['gestus_volunteer_phonenumber']: '';
        $email = isset($_POST['gestus_volunteer_email']) ? $_POST['gestus_volunteer_email']: '';
        $birthday = isset($_POST['gestus_volunteer_birthday']) ? $_POST['gestus_volunteer_birthday']: '';
        $gender = isset($_POST['gestus_volunteer_gender']) ? $_POST['gestus_volunteer_gender']: '';

        $attest = isset($_POST['attest']) ? $_POST['attest']: '';
        $noattest = isset($_POST['noattest']) ? $_POST['noattest']: '';

        


        if (! isset($_POST['gestus_volunteer_nonce'])) {
            return $post_id;
        }

        $nonce = $_POST['gestus_volunteer_nonce'];
        if (! wp_verify_nonce( $nonce, 'gestus_volunteer' )) {
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
            'adresse' => $adresse,
            'city' => $city,
            'email' => $email,
            'phonenumber' => $phonenumber,
            'postalcode' => $postalcode,
            'age' => $birthday,
            'gender' => $gender,
            'approved' => isset($_POST['gestus_volunteer_approved']) ? 1 : 0,
            'featured' => isset($_POST['gestus_volunteer_featured']) ? 1 : 0,
            'user_ip' =>  $user_ip,
            'attest' => $attest,
            'noattest' => $noattest
        );
        update_post_meta( $post_id, '_gestus_volunteer_key', $data );
    }


    public function sort_order_custom_data( $columns )
    {

       

        $title = $columns['title'];
        $date = $columns['date'];

        unset ( $columns['title'], $columns['date']);
     
       // $columns['title'] = $title;
        $columns['member'] = 'Navn';
        $columns['age'] = 'Alder';
        $columns['type'] = 'Frivillig for';
        $columns['phonenumber'] = 'Telefonnummer';
        $columns['gender'] = 'Køn';
        $columns['email'] = 'E-mail adresse';
        $columns['approved'] = 'Godkendt';
       // $columns['featured'] = 'Fremvises';
        $columns['user_ip'] = 'ip adresse';
        $columns['date'] = 'Ansøgt d.';


        return $columns;
    }

    public function sort_order_custom_columns ($column, $post_id){


        
        $data = get_post_meta( $post_id, '_gestus_volunteer_key', true );

 
       
        $name = isset($data['name']) ? $data['name'] : '';
        $gend = isset($data['gender']) ? $data['gender'] : '';

        $gender = ($gend == 'man') ? '<i class="fa fa-male fa-2x" style="color:#02A3FE"></i>' : '<i class="fa fa-female fa-2x" style="color:#FF007F"></i>';
        $type = isset($data['type']) ? $data['type'] : '';
        $email = isset($data['email']) ? $data['email'] : '';
        $phone = isset($data['phonenumber']) ? $data['phonenumber'] : '';
        $user_ip = isset($data['user_ip']) ? $data['user_ip'] : '';
        $email = isset($data['email']) ? $data['email'] : '';
        $alder = isset($data['age']) ? $this->get_age($data['age']): '';

        $attest = isset($data['attest']) ? $data['attest']  : '';

        $noattest = isset($data['noattest']) ? $data['noattest'] : '';

        
      
        $approved = isset($data['approved']) && $data['approved'] === 1 ? '<i class="dashicons dashicons-thumbs-up" ></i>' : '<i class="dashicons dashicons-thumbs-down" ></i>';
        $featured = isset($data['featured']) && $data['featured'] === 1 ? '<i class="dashicons dashicons-thumbs-up" ></i>' : '<i class="dashicons dashicons-thumbs-down" ></i>';

        switch($column)
        {

            case 'member':
                
                echo '<strong>' . $name  .'</strong>';
            break;

            case 'age':

                echo $alder;

            break;

            case 'user_ip':

                echo $user_ip;

            break;

            case 'type':

                $style = 'style="margin: 0;"';

                if(!empty($attest) && is_array($attest)){
                foreach($attest as $att) { echo '<p '.  $style .'>' .$att. '</p>'; }
                }else if(!empty($attest))
                { echo '<p '.  $style .'>' .$attest . '</p>';}

                if(!empty($noattest) && is_array($noattest)){
                foreach($noattest as $noatt) {  echo '<p '.  $style .'>' .$noatt. '</p>'; }
                }else if(!empty($noattest))
                { echo '<p '.  $style .'>' .$noattest . '</p>';}

            break;

            case 'gender':

                echo $gender ;

            break;

            case 'email':
                echo '<a href="mailto:'.$email.'">'.$email.'</a>';
            break;

            case 'phonenumber':
                echo '<a href="tel:'.$phone.'">'.$phone.'</a>';
            break;

            case 'firstname':
                echo '<strong>' . $name . '</strong><br>'. $user_ip;
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

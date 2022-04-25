<?php


/**
 * 
 * @package gestus_nord
 */

namespace Inc\Base ;

use WP_Query;
use WP_Error;

use Inc\Api\SettingsApi;
use Inc\Base\BaseController;
use Inc\Base\ResponseMailController;

use Inc\Base\ControllerTypeMailTags;

use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\AbstractDeviceParser;


use Inc\Base\MailController;

class ApplicantsController extends BaseController
{
    public $mail_tagger;

    public $subpages = array();
   
    public $numfiles;

    public $filename;

    public $applicant_data = array();

    public $save_applicant_data = array();

    public $child_data = array();

    public $save_child = array();

    public $cont_data = array();

    public $save_contacts = array();

    public $aid_form_shortcode = 'aid_form_shortcode';

    public $post_id;

    public $postID;

    public $postdata = array();

    public $mailer;

    public $dd;

    public $mail_info = array();

    
    public function register()
    {

      

        $this->mailsender = new MailController();
        
        if ( !$this->activated('aid_manager')) return;

        $this->settings = new SettingsApi();


        $this->mailer = new ResponseMailController();

        $this->mail_tagger = new ControllerTypeMailTags();


        $this->email_scaffold = array();

           
        add_action('init', array( $this, 'parents'));

        add_action('init', array( $this, 'all_contacts'));

        add_action('init', array( $this, 'all_kids'));     

        add_action('add_meta_boxes' , array( $this, 'add_applicants_metaboxes'));

        add_action('save_post' , array( $this, 'save_applicant_meta_box'));

        add_action('init', array($this, 'register_gestus_applicants'));

        add_shortcode( $this->aid_form_shortcode , array($this, 'aid_form_shortcode') );

        add_action('manage_applicants_posts_columns', array( $this , 'sort_order_custom_data'));

        add_action('manage_applicants_posts_custom_column', array( $this , 'sort_order_custom_columns'), 10 , 2);
 
        add_filter('manage_edit_applicants_sortable_columns' , array( $this, 'sortable_order_rows'));


       




        
        add_action('wp_ajax_submit_gestus_applicants' , array( $this , 'submit_gestus_applicants'));

        add_action('wp_ajax_nopriv_submit_gestus_applicants' , array( $this , 'submit_gestus_applicants'));

        add_action( 'wp_ajax_nopriv_file_upload', 'file_upload_callback' );
        
        $this->settings->addSubPages( $this->subpages )->register();

       // add_filter('pre_get_posts', array($this, 'order_post_type'));

      
    }


/**
 * 
 * register applicant post type 
 */
public function register_gestus_applicants()
{


    $labels = array(

        'name' => 'Applicants',
        'singular_name' => 'Applicant',
        'menu_name' => 'Ansøgere'
    );
    
    $args = array(

        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'show_ui' => true, 
        'menu_icon' => 'dashicons-testimonial',
        'exclude_from_search' => true,
        'publicly_queryable' => false,
        'show_in_menu' => 'gestus_nord_admin',
        'supports' => array('title', 'editor' , 'taxonomies'),
        'capabilities' => array(
            'create_posts' => false, // prevent users from creating new posts false or true
        ),
        'map_meta_cap' => true
       
        
    );

    
    register_post_type('applicants', $args);
}


 



/**
 * 
 * asign template to gestus_aid post type
 * 
 */
public function aid_form_shortcode() {



    
    ob_start();


 
    //gestus default form stylesheet
    wp_enqueue_style( 'gestus_style_sheet' );  

    //default form stylesheet
    wp_enqueue_style( 'form-style' );  
 
    wp_enqueue_script("aidform-script");

    wp_enqueue_style( 'load-fa' ); 


    require_once("$this->plugin_path/page-templates/aid_form.php");   

    wp_enqueue_script("validation-script");

    wp_enqueue_script("zip_fetcher");



    return ob_get_clean();


}




public function submit_gestus_applicants($_post)
{

    AbstractDeviceParser::setVersionTruncation(AbstractDeviceParser::VERSION_TRUNCATION_NONE);   
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $dd = new DeviceDetector($userAgent);


    /***
     * 
     * 
     * detecting devises 
     */


    $dd->parse();

    if ($dd->isBot()) {
      // handle bots,spiders,crawlers,...
      $botInfo = $dd->getBot();
    } else {
      $clientInfo = $dd->getClient(); // holds information about browser, feed reader, media player, ...
      $osInfo = $dd->getOs();
      $device = $dd->getDeviceName();
      $brand = $dd->getBrandName();
      $model = $dd->getModel();
    }

    $devise_info = array(

        'bot_info'     => isset($botInfo) ? $botInfo : '',
        'client_info'   => isset($clientInfo) ? $clientInfo : '',
        'osInfo'        => isset($osInfo) ? $osInfo : '',
        'device'        => isset($device) ? $device : '',
        'brand'         => isset($brand) ? $brand : '',
        'model'         => isset($model) ? $model : ''
    );
     /**
      * device edns here
      */


    $sender = $_POST['firstname1']. ' ' . $_POST['lastname1'] .' ' . $_POST['phonenumber1'];

   /* if(post_exists( $sender ) && $_POST['aid_type'] != 'Gestus Akuthjælp'){

        $return = array(
            'status' => 'success',
            
        );
        wp_send_json($return);

        wp_die();
        
    }
*/
    $title = $_POST['aid_type'];

    $_id = $_POST['post_ID'];

    $comments = $_POST['comments'];
   
    $user_ip = $_POST['user_ip'];

    $pick_up = $_POST['pickup'];

    $aid_id = $_POST['aid_id'];

    $kids_total = $_POST['kids_total'];

    $moreactivities = $_POST['moreactivities'];




    $args = array(
        'post_title' => $sender,
        'post_content' => $comments ,
        'post_author' => 1,
        'post_status' => 'publish',
        'post_type' => 'applicants',
       
    );


    $this->postID = wp_insert_post($args);

    $post_meta = 
    array(

        '_aidtype' => $title,
        'post_id'  => $_id
    );

    update_post_meta($this->postID , '_aidtype', $post_meta);



    $data = array(

        
        
        'user_ip' => $user_ip,
        'approved' => 0,
        'approved_by_userid' => '',
        'approved_email' => '',
        'approved_date' => '',
        'aid_type' => $title,
        'aid_id' => $aid_id,
        'post_id'  => $_id,
        'used_device' => $devise_info,
        'pickup' => $pick_up,
        'kids_total' => $kids_total,
        'parents' => $this->applicant_data,
        'contacts' =>  $this->cont_data,
        'kids' => $this->child_data,
        'moreactivities' => $moreactivities


        
        

    );



    $email_args = array(

        'option_name'   => 'email_scaffold',
        'parent_name'   => 'email_tags',
        'post_type'     => 'applicants',
        'scaffold'      => $data

    );

    
    $this->mail_tagger->mail_tag_sanitizer( $email_args );

  

    update_post_meta($this->postID , '_gestus_applicants_key', $data);


    if ($this->postID) {
        $return = array(
            'status' => 'success',
            'ID' => $this->postID
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




public function parents($_post){

   // determine if there is one or two applicants in this request for aid

   if(isset($_POST['firstname1'])){

        $this->mailer->sendmail($_POST['email1'], 
        'Gestus Nord - Din ansøgning er modtaget', 
        'Hej '. $_POST['firstname1'] .'  

Gestus Nord har nu modtaget din ansøgning. Du vil blive kontaktet når den er blevet behandlet.

Med venlig hilsen
        
Gestus Nord');

       


  
   $single = ( !isset($_POST['firstname2']) ) ? 1 : 2 ;


   //loop through all applicant posts
   for ($x = 1; $x <= $single; $x++) {


       $this->applicant_data[] = array(
       
          
                'post_id' => isset($_POST['post_ID']) ? $_POST['post_ID'] : '',
       
               'firstname' => isset($_POST['firstname'. $x]) ? $_POST['firstname'. $x] : '',
       
               'lastname' => isset($_POST['lastname'. $x]) ? $_POST['lastname'. $x] : '',
       
               'address' => isset($_POST['address'. $x]) ? $_POST['address'. $x] : '',
       
               'postalcode' => isset($_POST['postalcode'. $x]) ? intval($_POST['postalcode'. $x]) : '',
       
               'city' => isset($_POST['city'. $x]) ? $_POST['city'. $x] : '',
       
               'phonenumber' => isset($_POST['phonenumber'. $x]) ? intval($_POST['phonenumber'. $x]) : '',
       
               'email' => isset($_POST['email'. $x]) ? $_POST['email'. $x] : '',
       
               'applicant_or_spouse' => isset($_POST['applicant_or_spouse'. $x]) ? $_POST['applicant_or_spouse'. $x] : '',
       
               'jobselect' => isset( $_POST['jobselect'. $x] ) ? $_POST['jobselect'. $x] : '',

               'file' =>  $this->file_upload_callback( $x , $_POST['phonenumber'. $x] , $_POST['firstname'. $x], $_POST['lastname'. $x], $_POST['aid_type']) 
       

                   
           
       );
       
}
}
}


public function all_kids(){

    if(!empty($_POST['kids_arr'])){

        $b = sanitize_text_field($_POST['kids_arr']);
    
        
        $array =  json_decode(json_encode($b), true);
        // strip kids_arr from hardbrackets
        $array = str_replace(array('[',']'),'',$array);
        
        // create array from kids_arr
        $num_array = explode(",", $array );
    
        //loop through all kids
        for ($x = 0; $x <= count($num_array)-1; $x++) {
    
    
                    $this->child_data[] = array (
                       
                        'gender' =>  $_POST['gender'. $num_array[$x]],
                        'name' =>  $_POST['name'. $num_array[$x]],
                        'age' =>  $_POST['age'. $num_array[$x]]
                    );


             }

      

        }

}


public function all_contacts(){

/**
 * 
 * loop trough contacts
 */

if(!empty($_POST['cont_arr'])){
    $a = sanitize_text_field($_POST['cont_arr']);
    //echo $a;
    
    $array =  json_decode(json_encode($a), true);
    // strip cont_arr from hardbrackets
    $array = str_replace(array('[',']'),'',$array);
    
    // create array from cont_arr
    $num_array = explode(",", $array );

                // loop through all contacts
        for ($x = 0; $x <= count($num_array)-1; $x++) {

            $this->cont_data[] = array (

                        
                'contact_name' => $_POST['contact_name'. $num_array[$x]],
                'contact_title' => $_POST['contact_title'. $num_array[$x]],
                'contact_email' => $_POST['contact_email'. $num_array[$x]],
                'contact_phonenumber' => $_POST['contact_phonenumber'. $num_array[$x]],
                    
            );

        }
     
    }



}

/**
 * 
 */
public function add_applicants_metaboxes(){

    add_meta_box(
        'gestus_applicants_parents', //id
        'Forældre',//title
        array( $this, 'render_applicant_parent_meta_box'),//callback
        'applicants', // screens / post types
        'normal',// context
        'default'// priority
            // $args
                    
    );

    add_meta_box(
        'gestus_applicants_kids', //id
        'Børn',//title
        array( $this, 'render_applicant_kids_meta_box'),//callback
        'applicants', // screens / post types
        'normal',// context
        'default'// priority
            // $args
                    
    );


    add_meta_box(
        'gestus_applicants_contacts', //id
        'Kontakter',//title
        array( $this, 'render_applicant_contact_meta_box'),//callback
        'applicants', // screens / post types
        'normal',// context
        'default'// priority
            // $args
                    
    );

    add_meta_box(
        'gestus_applicants_approve_box', //id
        'Godkendelse',//title
        array( $this, 'render_applicant_approve_meta_box'),//callback
        'applicants', // screens / post types
        'side',// context
        'default'// priority
            // $args
                    
    );

    add_meta_box(
        'gestus_applicants_device_box', //id
        'Device Data',//title
        array( $this, 'render_applicant_device_meta_box'),//callback
        'applicants', // screens / post types
        'side',// context
        'default'// priority
            // $args
                    
    );/**/


    add_meta_box(
        'gestus_applicants_activity_box', //id
        'Accept for yderligere kontakt',//title
        array( $this, 'render_moreactivities_meta_box'),//callback
        'applicants', // screens / post types
        'side',// context
        'default'// priority
            // $args
                    
    );/**/

    

}

/**
 * 
 * functoion for rendering all parents meta boxes
 * 
 */
public function render_applicant_parent_meta_box($post){

    $data = get_post_meta( $post->ID, '_gestus_applicants_key', true );


    echo '<div id="myModal" class="modal">
    <span class="close">&times;</span>
    <img class="modal-content" id="img01">
    <div id="caption"></div>
    </div>';

    $parents = isset($data['parents']) ? $data['parents'] : '' ;


   echo '<section class="gestus-block-columns">';
   //echo count($parents);
    for($i = 0; $i < count($parents); $i++)
    {

        switch(strtolower(pathinfo($parents[$i]['file'], PATHINFO_EXTENSION))){

            case 'pdf':
                $file = '<object data="'.$parents[$i]['file'].'"   style="width: 100%; height:80%;over-flow:hidden;display:block" type="application/pdf"><iframe id="fil'.$i.'" class="myImg" onclick="get_modal(this.id)" src="'.$parents[$i]['file'].'"></iframe></object>';
            break;  

            default:
                $file = '<img style="width:100%" src="'.$parents[$i]['file'].'" id="fil'.$i.'" class="myImg" onclick="get_modal(this.id)" >';
            break;

        }


        
        echo '<div class="gestus-block-column">';

        echo '<article class="gestus-block-columns">';

        echo '<main class="gestus-block-column">';

        echo '<header><h3>'. $parents[$i]['applicant_or_spouse'] .'</h3></header>';
       



        echo'<div class="components-base-control">'.
            '<div class="components-base-control__field">'.
            '<span for="firstname'.$i.'" class="components-base-control__label">Fornavn</span>'.
            '<input type="text" class="components-text-control__input" id="firstname'.$i.'"
            name="firstname'.$i.'" value="'.$parents[$i]['firstname'].'" style="width:100%;" readonly>'.
            '</div></div>';

        echo'<div class="components-base-control">'.
        '<div class="components-base-control__field">'.
        '<span for="lastname'.$i.'" class="components-base-control__label">Efternavn</span>'.
        '<input type="text" class="components-text-control__input" id="lastname'.$i.'"
        name="lastname'.$i.'" value="'.$parents[$i]['lastname'].'" style="width:100%;" readonly>'.
        '</div></div>';

        echo'<div class="components-base-control">'.
        '<div class="components-base-control__field">'.
        '<span for="address'.$i.'" class="components-base-control__label">Addresse</span>'.
        '<input type="text" class="components-text-control__input" id="address'.$i.'"
        name="address'.$i.'" value="'.$parents[$i]['address'].'" style="width:100%;" readonly>'.
        '</div></div>';

        echo'<div class="components-base-control">'.
        '<div class="components-base-control__field">'.
        '<span for="postalcode'.$i.'" class="components-base-control__label">Postnummer</span>'.
        '<input type="text" class="components-text-control__input" id="postalcode'.$i.'"
        name="postalcode'.$i.'" value="'.$parents[$i]['postalcode'].'" style="width:100%;" readonly>'.
        '</div></div>';

        echo'<div class="components-base-control">'.
        '<div class="components-base-control__field">'.
        '<span for="city'.$i.'" class="components-base-control__label">By</span>'.
        '<input type="text" class="components-text-control__input" id="city'.$i.'"
        name="city'.$i.'" value="'.$parents[$i]['city'].'" style="width:100%;" readonly>'.
        '</div></div>';

        
        echo'<div class="components-base-control">'.
        '<div class="components-base-control__field">'.
        '<span for="phonenumber'.$i.'" class="components-base-control__label">Telefonnummer</span>'.
        '<input type="text" class="components-text-control__input" id="phonenumber'.$i.'"
        name="phonenumber'.$i.'" value="'.$parents[$i]['phonenumber'].'" style="width:100%;" readonly>'.
        '</div></div>';

        echo'<div class="components-base-control">'.
        '<div class="components-base-control__field">'.
        '<span for="email'.$i.'" class="components-base-control__label">Email addresse</span>'.
        '<input type="text" class="components-text-control__input" id="email'.$i.'"
        name="email'.$i.'" value="'.$parents[$i]['email'].'" style="width:100%;" readonly>'.
        '</div></div>';

        echo'<div class="components-base-control">'.
        '<div class="components-base-control__field">'.
        '<span for="jobselect'.$i.'" class="components-base-control__label">Beskæftigelse</span>'.
        '<input type="text" class="components-text-control__input" id="jobselect'.$i.'"
        name="jobselect'.$i.'" value="'.$parents[$i]['jobselect'].'" style="width:100%;" readonly>'.
        '</div></div>';

        echo '<input type="hidden" class="components-text-control__input" id="applicant_or_spouse'.$i.'"
        name="applicant_or_spouse'.$i.'" value="'.$parents[$i]['applicant_or_spouse'].'" style="width:100%;" readonly>';

        echo '</main>';
        echo '<figure class="gestus-block-column">';
        echo '<header><h3>Indkomstsdokumentation</h3></header>';
        echo $file;
        echo '</figure>';
        echo'</article>';
        echo '</div>';
       

    }
    echo '</section>';

    

}

public function render_applicant_kids_meta_box($post){


    $data = get_post_meta( $post->ID, '_gestus_applicants_key', true );

    $kidslist = isset($data['kids']) ? $data['kids'] : '';

    echo '<section class="gestus-block-columns">';


    for($i=0; $i < count($kidslist); $i++)
    {

        $gender = $kidslist[$i]['gender'] =='boy' ? 'Dreng' : 'Pige';

        echo '<article class="gestus-block-column">';



        echo'<div class="components-base-control">'.
            '<div class="components-base-control__field">'.
            '<span for="name'.$i.'" class="components-base-control__label">Fornavn: </span>'.
            '<input type="hidden" class="components-text-control__input" id="name'.$i.'"
            name="name'.$i.'" value="'.$kidslist[$i]['name'].'" style="width:100%;" readonly>'.
            '<span>'. $kidslist[$i]['name'] .'</span>'.
            '</div></div>';

            echo'<div class="components-base-control">'.
            '<div class="components-base-control__field">'.
            '<span for="gender'.$i.'" class="components-base-control__label">Køn: </span>'.
            '<input type="hidden" class="components-text-control__input" id="gender'.$i.'"
            name="gender'.$i.'" value="'.$kidslist[$i]['gender'].'" style="width:100%;" readonly>'.
            '<span>'. $gender .'</span>';
            '</div></div>';

            
            echo'<div class="components-base-control">'.
            '<div class="components-base-control__field">'.
            '<span for="age'.$i.'" class="components-base-control__label">Fødselsdato: </span>'.
            '<input type="hidden" class="components-text-control__input" id="age'.$i.'"
            name="age'.$i.'" value="'. $kidslist[$i]['age'].'" style="width:100%;" readonly>'.
            '<span>'. $kidslist[$i]['age'] .'</span>'.
            '<p>'. $this->get_age($kidslist[$i]['age']) .'</p>'.
            '</div></div>';

            echo '</article>';
           
    
        }
        echo '</section>';

    


}

public function render_applicant_contact_meta_box($post){

    

    $data = get_post_meta( $post->ID, '_gestus_applicants_key', true );

    $contactslist = isset($data['contacts']) ? $data['contacts'] : false;

    if(!$contactslist){

        return;
    }

    echo '<section class="gestus-block-columns">';


    for($i=0; $i < count($contactslist); $i++)
    {

        

        echo '<article class="gestus-block-column">';



        echo'<div class="components-base-control">'.
            '<div class="components-base-control__field">'.
            '<span for="contact_name'.$i.'" class="components-base-control__label">Navn: </span>'.
            '<input type="hidden" class="components-text-control__input" id="contact_name'.$i.'"
            name="contact_name'.$i.'" value="'.$contactslist[$i]['contact_name'].'" style="width:100%;" readonly>'.
            '<span>'. $contactslist[$i]['contact_name'] .'</span>'.
            '</div></div>';

            echo'<div class="components-base-control">'.
            '<div class="components-base-control__field">'.
            '<span for="contact_title'.$i.'" class="components-base-control__label">Navn: </span>'.
            '<input type="hidden" class="components-text-control__input" id="contact_title'.$i.'"
            name="contact_title'.$i.'" value="'.$contactslist[$i]['contact_title'].'" style="width:100%;" readonly>'.
            '<span>'. $contactslist[$i]['contact_title'] .'</span>'.
            '</div></div>';

            echo'<div class="components-base-control">'.
            '<div class="components-base-control__field">'.
            '<span for="contact_email'.$i.'" class="components-base-control__label">Email: </span>'.
            '<input type="hidden" class="components-text-control__input" id="contact_email'.$i.'"
            name="contact_email'.$i.'" value="'.$contactslist[$i]['contact_email'].'" style="width:100%;" readonly>'.
            '<span>'. $contactslist[$i]['contact_email'] .'</span>';
            '</div></div>';

            
            echo'<div class="components-base-control">'.
            '<div class="components-base-control__field">'.
            '<span for="contact_phonenumber'.$i.'" class="components-base-control__label">Telefonnummer: </span>'.
            '<input type="hidden" class="components-text-control__input" id="contact_phonenumber'.$i.'"
            name="contact_phonenumber'.$i.'" value="'. $contactslist[$i]['contact_phonenumber'].'" style="width:100%;" readonly>'.
            '<span>'. $contactslist[$i]['contact_phonenumber'] .'</span>'.
           
            '</div></div>';

            echo '</article>';
           
    
        }
        echo '</section>';
}

public function render_applicant_device_meta_box($post)
{



    $data = get_post_meta( $post->ID, '_gestus_applicants_key', true );


if(isset($data['used_device'])){
?>

<div class="components-base-control">
<div class="components-base-control__field">
<?php


echo '<ul>';
foreach($data['used_device'] as $key => $value)
{

    if(is_array($value)){



        foreach($value as $newkey => $newvalue){



                  echo '<li>'. $newkey . ' - '. $newvalue .'</li>';
            
          
        }



    }else{

            
        if($value){
            echo '<li>'. $key . ' - '. $value .'</li>';
        }
    
    }


}
echo '</ul>';

?>
</div>
</div>

<?php

}



}

public function render_moreactivities_meta_box($post){


    $data = get_post_meta( $post->ID, '_gestus_applicants_key', true );

    $moreactivities = isset($data['moreactivities']) ? 'Vil gerne kontaktes vedrørende andre aktiviteter': 'Vil ikke kontaktes eller har ikke taget stilling vedr. andre aktiviteter';

    echo $moreactivities;

}



public function render_applicant_approve_meta_box($post){

    

    wp_nonce_field( 'gestus_applicants', 'gestus_applicants_nonce' );

    $data = get_post_meta( $post->ID, '_gestus_applicants_key', true );


    $emaildata = isset($data['aid_id']) ? get_post_meta( $data['aid_id'], '_gestus_aid_key', true ) : '';

    $from_mail = isset($emaildata['email_from']) ? $emaildata['email_from'] : (isset($data['from_mail']) ? $data['from_mail'] : '');

    


 

    $from_mail_label = isset($emaildata['email_from']) ? ' Står som afsender på email ': 'Sæt som afsender af email';

    $set_approval = isset($data['set_approval']) ? $data['set_approval'] : false;

   // var_dump($set_approval);



    
    $approved_date = isset($data['approved_date']) ? $data['approved_date'] : date("dd-mm-yyyy");

    $approved = isset($data['approved'])  ? $data['approved'] : false;

  //  var_dump($approved);

    $approve_label = isset($data['approved'])  ? ($data['approved'] == 0 ? 'Afvist ' : 'Godkendt ') : '';




    $approved_by_userid = isset($data['approved_by_userid']) ? $data['approved_by_userid'] : get_current_user_id();

    if($approved_by_userid){
        
        $the_user = get_user_by('id', $approved_by_userid);
        $the_user_id = $the_user->user_login;
        $the_user_email = $the_user->user_email;
       

    }





?>

<?php if (!isset( $data['set_approval'] ))  {?>
        
    
    
<div class="meta-container">
    <label class="inline strong left" for="set_approval">Angiv godkendelse</label>
    <div class="right  inline">
        <div class="ui-toggle "><input type="checkbox" id="set_approval" name="set_approval"  value="1" <?php echo $set_approval ? 'checked' : ''; ?> onchange="hide_tags('test');">
            <label for="set_approval">
                <div></div>
            </label>
        </div>
    </div>
</div>


<div id="test" style="<?php echo !isset($set_approval) ? 'display:block' : 'display:none'; ?>">
<div class="meta-container" >
    <label class="inline strong left" for="denial">Afvis</label>
    <div class="right  inline">
        <div class="ui-toggle "><input type="checkbox" id="denial" name="approved" value="0" <?php echo !$approved && $set_approval ? 'checked' : ''; ?>  onchange="SetSel(this , 'approved');">
            <label for="denial">
                <div></div>
            </label>
        </div>
    </div>
</div>
<div class="meta-container" >
    <label class="inline strong left" for="approve">Godkend</label>
    <div class="right  inline">
        <div class="ui-toggle "><input type="checkbox" id="approve" value="1" <?php echo $approved && $set_approval ? 'checked' : ''; ?> name="approved" onchange="SetSel(this , 'approved');" >
            <label for="approve">
                <div></div>
            </label>
        </div>
    </div>
</div>
</div>
<?php } ?>



<?php if (isset( $data['set_approval'] ))  {?>

<div class="components-base-control">
    <div class="components-base-control__field">
        <label for="approved_by_userid" class="components-base-control__label"><?php echo $approve_label ?> af</label>
        <input type="hidden" class="components-text-control__input" id="approved_by_userid"
            name="approved_by_userid" value="<?php echo $approved_by_userid?>" style="width:100%;" readonly>
        <input type="text" value="<?php echo isset($the_user_id) ? $the_user_id : '';?>" style="width:100%;" readonly>
        </div>
</div>

<div class="components-base-control">
    <div class="components-base-control__field">
        <label for="approved_by_userid" class="components-base-control__label">E-mail:</label>
        <input type="text" value="<?php echo isset($the_user_email) ? $the_user_email : '';?>" style="width:100%;" readonly>
        </div>
</div>


<div class="components-base-control">
    <div class="components-base-control__field">
        <label for="approved_date"><?php echo $approve_label ?> den </label>
        <input type="text" id="approved_date" name="approved_date"
            value="<?php echo $approved_date?>" style="width:100%;" readonly>
    </div>
</div>
<?php }?>

<div class="components-base-control">
    <div class="components-base-control__field">
        <label for="from_mail"><?php echo   $from_mail_label ?></label>
        <input type="email" id="from_mail" name="from_mail"
            value="<?php echo $from_mail?>" required style="width:100%;" >
    </div>
</div>






<?php

}

public function save_applicant_meta_box($post_id)
    {

   


        $this->post_id = $post_id;



        global $current_user;
        wp_get_current_user();

        //get values not ment to be editable directly from meta data. preventing dom manipulation of input fields
        $data = get_post_meta( $post_id, '_gestus_applicants_key', true );

        $emaildata = isset($data['aid_id']) ? get_post_meta( $data['aid_id'], '_gestus_aid_key', true ) : '';
 
        
        $from_mail = isset($emaildata['email_from']) ? $emaildata['email_from'] : (isset($_POST['from_mail']) ? $_POST['from_mail'] : 'info@gestusnord.dk');


        $user_ip = isset($data['user_ip']) ? $data['user_ip'] : $this->ip;

        $used_device = isset($data['used_device']) ? $data['used_device'] : '' ;

  
        if (! isset($_POST['gestus_applicants_nonce'])) {
            return $post_id;
        }

        $nonce = $_POST['gestus_applicants_nonce'];
        if (! wp_verify_nonce( $nonce, 'gestus_applicants' )) {
            return $post_id;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        if (! current_user_can( 'edit_post', $post_id ) ) {
            return $post_id;
        }

        $aid_id = isset($data['aid_id']) ? $data['aid_id'] : '';

        $aidtype = isset($data['aid_type']) ? $data['aid_type'] : '';
        /**
         * 
         * 
         * prepare parents box
         */
        $parents = isset($data['parents']) ? $data['parents'] : '' ;


        /**
         * 
         * prepare contacts
         */
        $contactslist = isset($data['contacts']) ? $data['contacts'] : false;

        /**
         * 
         * prepare kids
         */
        $kidslist = isset($data['kids']) ? $data['kids'] : '';

        $appro = isset($_POST['approved']) ? $_POST['approved'] : (isset($data['approved']) ? $data['approved'] : 0 );


        $set_approval = isset($_POST['set_approval'])  ?  $_POST['set_approval'] : (isset($data['set_approval']) ? $data['set_approval'] : 0 ) ;
    



        //afviste email
        if(isset($data['set_approval']) != 1 && isset($_POST['approved']) && $_POST['approved'] == 0 ){
       
            $this->mailsender->refused_aid($parents, $from_mail, $post_id);
    
        }   


        //godkendt email
        if(isset($data['set_approval']) != 1 && isset($_POST['approved']) && $_POST['approved'] == 1 ){
          
        $this->mailsender->granted_aid($parents, $from_mail, $post_id);

        }


      $data = array(
        'set_approval' => $set_approval, 
        'approved' => $appro,
        'approved_by_userid' => isset($_POST['approved_by']) ? $_POST['approved_by'] : get_current_user_id() ,


        'approved_date' => isset($_POST['approved_date']) && !isset($data['set_approval']) ? current_time('d-m-Y H:i:s') : $data['approved_date'],

        'user_ip' =>  $user_ip,

        'parents' => $parents,

        'contacts' => $contactslist,
        
        'kids' => $kidslist,

        'aid_id' => $aid_id,

        'aid_type' => $aidtype,

        'from_mail' => $from_mail,

        'used_device' => $used_device
        
        
    );

            update_post_meta( $post_id, '_gestus_applicants_key', $data );


        
        
    }



/**
* 
* 
* Uploading file for file of income
* 
*/
   public function file_upload_callback( $x , $pid, $F, $L, $aid_type){

        

            //users firstname
            $F = $this->wpartisan_sanitize_file_name( $F );

            //users lastname
            $L = $this->wpartisan_sanitize_file_name( $L );

            $aid_type = $this->wpartisan_sanitize_file_name( $aid_type );
         
            $arr_img_ext = array('image/png', 'image/jpeg', 'image/jpg',  'application/pdf');
            
            $who = $this->wpartisan_sanitize_file_name( $_POST['applicant_or_spouse'. $x] );

            if($_FILES['file'. $x]['name'] && in_array($_FILES['file'. $x]['type'], $arr_img_ext)){

               

                echo '<script> console.log("postid er "'. $pid .' );</script>';

                $year = $this->wpartisan_sanitize_file_name(date("Y"));
                $month = $this->wpartisan_sanitize_file_name(date("F"));

            if (!file_exists($_SERVER['DOCUMENT_ROOT'] ."/wp-content/uploads/gestus_aid/dokumentation/".strtolower($aid_type)."/".$year."/".$month."/".$_POST['phonenumber1']."/")) {
                mkdir($_SERVER['DOCUMENT_ROOT'] ."/wp-content/uploads/gestus_aid/dokumentation/".strtolower($aid_type)."/".$year."/".$month."/".$_POST['phonenumber1']."/", 0777, true); // create folder if it does not exist
            }

            

            $path = $_SERVER['DOCUMENT_ROOT'] ."/wp-content/uploads/gestus_aid/dokumentation/".strtolower($aid_type)."/".$year."/".$month."/".$_POST['phonenumber1']."/"; // file path
            $filename = $_FILES['file'. $x]['name']; // get file name
            $file_ext = substr($filename, strripos($filename, '.')); // get file extension
            $newfilename =  $who . "_" . $pid . "_" . $F . "_" . $L. $file_ext;

            if (!empty($_FILES['file'. $x]['name'])) {


                if (move_uploaded_file($_FILES['file' . $x]['tmp_name'], $path . $newfilename)) {

                    return wp_get_upload_dir()['baseurl']."/gestus_aid/dokumentation/".strtolower($aid_type)."/".$year."/".$month."/".$_POST['phonenumber1']."/" . $newfilename;

                }else {
                    echo "There was an error uploading the file, please try again!";
                }}

        }
            
    }




            public function sort_order_custom_data( $columns )
            {
        
                
        
                $title = $columns['title'];
                $date = $columns['date'];

                $author = $columns['author'];
        
                unset ( $columns['title'], $columns['date'], $columns['author']);
             
                $columns['title'] = $title;

                $columns['aid_type'] = 'Type hjælp';
                
                $columns['jobselect'] = 'indkomst';

                $columns['kids'] = 'Børn';


                $columns['contacts'] = 'Kontakter';

                $columns['approved'] = 'Godkendt';

              //  $columns['approved_by_userid'] = 'Godkendt af';

                $columns['date'] = $date;
        
        
                return $columns;
            }
        
            public function sort_order_custom_columns ($column, $post_id){

               

                        //get the type of aid this post is targeted
                $post_page_tag = is_array(get_post_meta( $post_id, '_aidtype', true )) ? get_post_meta( $post_id, '_aidtype', true )['_aidtype'] : get_post_meta( $post_id, '_aidtype', true );

                //var_dump(get_post_meta( $post_id, '_aidtype', true ));
        
                $data = get_post_meta( $post_id, '_gestus_applicants_key', true );

               

                $parentslist = isset($data['parents'] ) && $data['parents'] != '' ? $data['parents'] : '';

        
                $kidslist = isset( $data['kids'] ) && $data['kids'] != '' ? $data['kids'] : '';

                $contactlist = isset( $data['contacts']) ? $data['contacts'] : '';
              
                $approved = isset($data['approved']) && intval($data['approved']) === 1  ? '<i class="dashicons dashicons-thumbs-up" ></i>' : '<i class="dashicons dashicons-thumbs-down" ></i>';

                $approved_by_userid = isset($data['approved_by_userid'])  ? $data['approved_by_userid'] .'<br>'. $data['approved_date'] : '';


                
                switch($column)
                {



                    case 'jobselect':

                        $this->parentlist($parentslist);

                    break;

                    case 'aid_type':

                        echo $post_page_tag;

                    break;
        
                    case 'approved':
                        echo $approved;
                    break;

                    case 'approved_by_userid':
                       // get_user_by( 'id', $approved_by_userid) ;
                    break;

                    case 'kids':

                       $this->kidslist($kidslist);

                    break;

                    case 'contacts':

                        $this->contactlist($contactlist);

                    break;

        


                }
        
            }
        
            public function sortable_order_rows ( $columns )
            {
        

        
                $columns['approved'] = 'approved';

                $columns['approved_by_userid'] = 'approved_by_userid';
        

        
                return $columns;
            }

            public function kidslist($args){

                
               $kid = '';
               for($i=0; $i < count($args); $i++)
               {

                if($args[$i]['name']){

                $kid .= $args[$i]['name']. ' - ' . $this->get_age($args[$i]['age']). '<br>';
                
                }


               }

               echo $kid;
               
            }

            public function contactlist($args){

                $contact = '';


                for($i = 0; $i < count($args); $i++){

                    if($args){
                    $contact .= $args[$i]['contact_name']. ' - ' . $args[$i]['contact_phonenumber'] . '<br>';
                    }
                }

                echo $contact;
            }

            public function parentlist($args)
            {

               // var_dump($args);

                $parent = '';

                for($i = 0; $i < count($args); $i++){

                    switch($args[$i]['applicant_or_spouse']){

                        case 'gift_med':

                            $par = 'Ægtefælle';

                        break;

                        case 'Ansøger':

                            $par = 'Ansøger';

                        break;

                        case 'Samlever':

                            $par = 'Samlever';

                        break;

                    }

                    if($args){
                        $parent .= $par. ' - ' . $args[$i]['jobselect'] . '<br>';
                    }
                }

                echo $parent;
            }
        



}
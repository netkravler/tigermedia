<?php


/**
 * 
 * @package gestus_nord
 */

namespace Inc\Base ;


use Inc\Api\SettingsApi;
use Inc\Base\BaseController;
use Inc\Base\ResponseMailController;


class ApplicantsController extends BaseController
{
    

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



    public function register()
    {

     
        
        if ( !$this->activated('aid_manager')) return;

        $this->settings = new SettingsApi();


        $this->mailer = new ResponseMailController();




           
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
        'menu_icon' => 'dashicons-testimonial',
        'exclude_from_search' => true,
        'publicly_queryable' => false,
        'supports' => array('title', 'editor' , 'taxonomies'),
        'capabilities' => array(
            'create_posts' => false, // prevent users from creating new posts false or true
        ),
        'map_meta_cap' => true
       
        
    );

    
    register_post_type('applicants', $args);
}

public function order_post_type($query) {
    if($query->is_admin) {
  
          if ($query->get('post_type') == 'applicants')
          {
            $query->set('orderby', 'title');
            $query->set('order', 'ASC');
          }
    }
    return $query;
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



    $sender = $_POST['firstname1']. ' ' . $_POST['lastname1'] .' ' . $_POST['phonenumber1'];

    if(post_exists( $sender ) && $_POST['aid_type'] != 'Gestus Akuthjælp'){

        $return = array(
            'status' => 'success',
            
        );
        wp_send_json($return);

        wp_die();
        
    }

    $title = $_POST['aid_type'];

    $comments = $_POST['comments'];
    $funds = $_POST['other_funds'];
    $user_ip = $_POST['user_ip'];

    $aid_type = $_POST['aid_type'];




    $args = array(
        'post_title' => $sender,
        'post_content' => $comments ,
        'post_author' => 1,
        'post_status' => 'publish',
        'post_type' => 'applicants',
       
    );


    $this->postID = wp_insert_post($args);

    update_post_meta($this->postID , '_aidtype', $title);



    $data = array(

        
        'funds' => $funds,
        'user_ip' => $user_ip,
        'approved' => 0,
        'approved_by_userid' => '',
        'approved_email' => '',
        'approved_date' => '',
        'parents' => $this->applicant_data,
        'contacts' =>  $this->cont_data,
        'kids' => $this->child_data,
        'aid_type' => $aid_type
        
        

    );

    


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
       
          
       
               'firstname' => $_POST['firstname'. $x],
       
               'lastname' => $_POST['lastname'. $x],
       
               'address' => $_POST['address'. $x],
       
               'postalcode' => intval($_POST['postalcode'. $x]),
       
               'city' => $_POST['city'. $x],
       
               'phonenumber' => intval($_POST['phonenumber'. $x]),
       
               'email' => $_POST['email'. $x],
       
               'applicant_or_spouse' => $_POST['applicant_or_spouse'. $x],
       
               'jobselect' => $_POST['jobselect'. $x],

               'file' => $this->file_upload_callback( $x , $_POST['phonenumber'. $x] , $_POST['firstname'. $x], $_POST['lastname'. $x], $_POST['aid_type'])
       

                   
           
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


public function render_applicant_approve_meta_box($post){

    wp_nonce_field( 'gestus_applicants', 'gestus_applicants_nonce' );

    $data = get_post_meta( $post->ID, '_gestus_applicants_key', true );

    




    $approved_by_userid = isset($data['approved_by_userid']) ? $data['approved_by_userid'] : '';

    if($approved_by_userid){
        
        $the_user = get_user_by('id', get_current_user_id());
        $the_user_id = $the_user->user_login;
        echo $the_user_id;

    }

    $approved_date = isset($data['approved_date']) ? $data['approved_date'] : '';

    $approved = isset($data['approved']) ? $data['approved'] : false;


?>
<div class="meta-container">
    <label class="inline strong left" for="gestus_applicants_approved">Godkend ansøgning</label>
    <div class="right  inline">
        <div class="ui-toggle "><input type="checkbox" id="gestus_applicants_approved" name="gestus_applicants_approved"
                value="1" <?php echo $approved ? 'checked' : ''; ?>>
            <label for="gestus_applicants_approved">
                <div></div>
            </label>
        </div>
    </div>
</div>

<?php if(isset($data['approved'])){ ?>

<div class="components-base-control">
    <div class="components-base-control__field">
        <label for="gestus_applicants_approved_email" class="components-base-control__label">Godkendt af e-mail</label>
        <input type="text" class="components-text-control__input" id="gestus_applicants_approved_email"
            name="gestus_applicants_approved_email" value="<?php //echo $approved_email?>" style="width:100%;" readonly>
    </div>
</div>

<div class="components-base-control">
    <div class="components-base-control__field">
        <label for="gestus_applicants_approved_by_userid" class="components-base-control__label">Godkendt af</label>
        <input type="text" class="components-text-control__input" id="gestus_applicants_approved_by_userid"
            name="gestus_applicants_approved_by_userid" value="<?php echo $approved_by_userid?>" style="width:100%;" readonly>
    </div>
</div>

<div class="components-base-control">
    <div class="components-base-control__field">
        <label for="gestus_applicants_approved_date">Godkendt den </label>
        <input type="datetime" id="gestus_applicants_approved_date" name="gestus_applicants_approved_date"
            value="<?php echo $approved_date?>" style="width:100%;" readonly>
    </div>
</div>


<?php
}
}

public function save_applicant_meta_box($post_id)
    {


        $this->post_id = $post_id;


      

        global $current_user;
        wp_get_current_user();

        //get values not ment to be editable directly from meta data. preventing dom manipulation of input fields
        $data = get_post_meta( $post_id, '_gestus_applicants_key', true );

        $user_ip = isset($data['user_ip']) ? $data['user_ip'] : $this->ip;

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


        /**
         * 
         * 
         * prepare parents box
         */
        $parents = isset($data['parents']) ? $data['parents'] : '' ;


        for($i = 0; $i < count($parents); $i++)
        {
            $this->save_applicant_data[] = array(
            
                   'firstname' => $_POST['firstname'. $i],
           
                   'lastname' => $_POST['lastname'. $i],
            
                   'address' => $_POST['address'. $i],
           
                   'postalcode' => intval($_POST['postalcode'. $i]),
           
                   'city' => $_POST['city'. $i],
           
                   'phonenumber' => intval($_POST['phonenumber'. $i]),
           
                   'email' => $_POST['email'. $i],
           
                   'applicant_or_spouse' => $_POST['applicant_or_spouse'. $i],
           
                   'jobselect' => $_POST['jobselect'. $i]
                   
                   
            
                   );
        
        }

        /**
         * 
         * prepare contacts
         */
        $contactslist = isset($data['contacts']) ? $data['contacts'] : false;

        for($i=0; $i < count($contactslist); $i++)
        {
        
         $this->save_contacts[] = array (
    
            'contact_name' => $_POST['contact_name'.$i],
            'contact_title' => $_POST['contact_title'.$i],
            'contact_email' => $_POST['contact_email'.$i],
            'contact_phonenumber' => $_POST['contact_phonenumber'.$i]
            
         );
        
        }
        
        /**
         * 
         * prepare kids
         */
        $kidslist = isset($data['kids']) ? $data['kids'] : '';
    
        for($i=0; $i < count($kidslist); $i++)
        {
        
            $this->save_child[] = array (
    
            'name' => $_POST['name'.$i],
            'gender' => $_POST['gender'.$i],
            'age' => $_POST['age'.$i]
            
            );
        
        }



        $data = array(
            'approved' => isset($_POST['gestus_applicants_approved']) ? 1 : 0,
            'approved_by_userid' => isset($_POST['gestus_applicants_approved']) ? get_current_user_id() : '',


            'approved_date' => isset($_POST['gestus_applicants_approved']) ? current_time('d-m-Y H:i:s') : '',

            'user_ip' =>  $user_ip,

            'parents' => $this->save_applicant_data,

            'contacts' => $this->save_contacts,
            
            'kids' => $this->save_child
            
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

                $columns['approved_by_userid'] = 'Godkendt af';

                $columns['date'] = $date;
        
        
                return $columns;
            }
        
            public function sort_order_custom_columns ($column, $post_id){

               

                        //get the type of aid this post is targeted
                $post_page_tag = get_post_meta( $post_id, '_aidtype', true );
        
                $data = get_post_meta( $post_id, '_gestus_applicants_key', true );

               

                $parentslist = isset($data['parents'] ) && $data['parents'] != '' ? $data['parents'] : '';

        
                $kidslist = isset($data['kids'] ) && $data['kids'] != '' ? $data['kids'] : '';

                $contactlist = isset($data['contacts']) ? $data['contacts'] : '';
              
                $approved = isset($data['approved']) && $data['approved'] === 1 ? '<i class="dashicons dashicons-thumbs-up" ></i>' : '<i class="dashicons dashicons-thumbs-down" ></i>';

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
                        get_user_by( 'id', $approved_by_userid) ;
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
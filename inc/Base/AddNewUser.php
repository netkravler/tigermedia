<?php

namespace Inc\Base;

use Inc\Tools\Session;
use Inc\Base\BaseController;

use WP_Error;

use Inc\Base\MailController;

class AddNewUser extends BaseController
{

   public $username;
   public $email;
   public $first_name;
   public $billing_company;
   public $billing_phone;
   public $billing_email;
   public $password;
   public $confirm_password;
   public $accept_contact;

    public $errors;

    public $mail_info = array();

public function register()
{

    $this->mailsender = new MailController();

    

    $this->first_name_label = 'Dit navn';

    $this->billing_company_label = 'Firmanavn';

    $this->billing_email_label = 'Fakturerings E-mail';

    $this->billing_phone_label = 'Telefonnummer';

    $this->accept_label = 'JA TAK - jeg vil gerne kontaktes med henblik på at blive erhvervspartner (koster 2000 kr. ekskl. moms årligt)';

    $this->user_password_label = 'Password';

    $this->confirm_user_password_label = 'Bekræft Password';

    // Register a new shortcode: [cr_custom_registration]
add_shortcode( 'auction_form', array( $this, 'custom_registration_shortcode' ));

add_action( 'login_footer', array($this,  'wpse330527_add_html_content' ));

}

public function registration_form(         
                        $username,
                        $email,
                        $first_name,
                        $billing_company,
                        $billing_phone, 
                        $billing_email, 
                        $password, 
                        $confirm_password,
                        $accept_contact
                        ) 
{


    

    echo '
    <style>
    .registerform {
        width:90%;
        margin-top: 20px;
        margin: 0 auto;
        padding: 26px 24px 46px;
        font-weight: 400;
        overflow: hidden;
        background: #fff;
        border: 1px solid #ccd0d4;
        box-shadow: 0 1px 3px rgba(0,0,0,.04);
    }

    input[type="text"], input[type="password"]{
        width:100%;
        padding: 5px;
        border-radius: 3px;
        margin-bottom: 5px;
        background-color: #fff;
    }
    input[type="submit"], input[type="checkbox"]{

        margin-top: 30px;
        padding: 5px;
        border-radius: 3px;
        background-color: #fff;
    }
    .error{

        font-size: 12px;
        font-weight: 600;
        color:yellow;
        
    }

    .registerform{
        border: none;
        background-color: transparent;
    }

    label, h4{

        color: #fff;
    }

    fieldset{

        border: none;
    }

    input[type="submit"]
    {
        padding: 10px 20px;

        border-radius: 15px;
    }
    
    </style>
    ';
   // var_dump($this->errors);
 
    echo '
    <form class="registerform" action="' . $_SERVER['REQUEST_URI'] . '" method="post">
    <fieldset>
    <legend><h4>Opret dig som auktionsdeltager her</h4></legend>
    <div>
    <label for="username">Brugernavn <strong>*</strong></label>
    <p><input type="text" class="input" name="username" value="' . ( isset( $_POST['username'] ) ? $username : null ) . '"></p>
    <small class="error">'. ( isset( $_POST['username'] ) ? $this->errors->errors['user_name_exist'][0] : '').'</small>
    <small class="error">'. ( isset( $_POST['username'] ) ? $this->errors->errors['user_length'][0]: '') .'</small>
    <small class="error">'. ( isset( $_POST['username'] ) ? $this->errors->errors['user_name_invalid'][0]: '') .'</small>
    </div>
     

     
    <div>
    <label for="email">E-mail <strong>*</strong></label>
    <p><input type="text" class="input" name="email" value="' . ( isset( $_POST['email']) ? $email : null ) . '"></p>
    <small class="error">'. ( isset( $_POST['email']) ? $this->errors->errors['email_empty'][0] : '') .'</small>
    <small class="error">'. ( isset( $_POST['email']) ? $this->errors->errors['email_exist'][0]: '') .'</small>
    <small class="error">'. ( isset( $_POST['email']) ? $this->errors->errors['email_invalid'][0]: '') .'</small>
    </div>
     

    <div>
    <label for="firstname"> ' . $this->first_name_label . '  <strong>*</strong></label>
    <p><input type="text" class="input" name="fname" value="' . ( isset( $_POST['fname']) ? $first_name : null ) . '"></p>
    <small class="error">'.  ( isset( $_POST['fname']) ? $this->errors->errors['first_name_empty'][0] :'') .'</small>
    </div>
     

    <div>
    <label for="billing_company">'. $this->billing_company_label .'  <strong>*</strong></label>
    <p><input type="text" class="input" name="billing_company" value="' . ( isset( $_POST['billing_company']) ? $billing_company : null ) . '"></p>
    <small class="error">'. ( isset( $_POST['billing_company']) ? $this->errors->errors['billing_company_empty'][0] : '') .'</small>
    </div>

    <div>
    <label for="billing_phone"> '. $this->billing_phone_label .'  <strong>*</strong></label>
    <p><input type="text" class="input" name="billing_phone" value="' . ( isset( $_POST['billing_phone']) ? $billing_phone : null ) . '"></p>
    <small class="error">'. ( isset( $_POST['billing_phone']) ? $this->errors->errors['billing_phone_empty'][0] : '').'</small>
    </div>

    <div>
    <label for="billing_email"> '. $this->billing_email_label .'  <strong>*</strong></label>
    <p><input type="text" class="input"  name="billing_email" value="' . ( isset( $_POST['billing_email']) ? $billing_email : null ) . '"></p>
    <small class="error">'. ( isset( $_POST['billing_email']) ? $this->errors->errors['billing_email_empty'][0] :'') .'</small>
    </div>
     


    <div>
    <label for="password"> '. $this->user_password_label .' <strong>*</strong></label>
    <p><input type="password" name="password" value="' . ( isset( $_POST['password'] ) ? $password : null ) . '"></p>
    <small class="error">'. ( isset( $_POST['password'] ) ? $this->errors->errors['password_empty'][0] : '') .'</small>
    <small class="error">'. ( isset( $_POST['password'] ) ? $this->errors->errors['password_nomatch'][0] :'' ).'</small>
    </div>

    <div>
    <label for="confirm_password"> '. $this->confirm_user_password_label .' <strong>*</strong></label>
    <p><input type="password" class="input" name="confirm_password" value="' . ( isset( $_POST['confirm_password'] ) ? $confirm_password : null ) . '"></p>
    <small class="error">'. ( isset( $_POST['confirm_password'] ) ? $this->errors->errors['confirm_password_empty'][0] :'') .'</small>
    </div>

         <div>
    <label for="accept_contact">
    <input type="checkbox" name="accept_contact" value="' . ( isset( $_POST['accept_contact']) ? 1 : 0 ) . '">
     '. ( isset( $_POST['accept_contact']) ? $this->accept_label : '') .' </label>
    </div>
    <br>
    <label>Hvis ikke du modtager en E-mail kort efter tilmelding, så kig evt. i spam</label>


    <p><input type="submit" name="submit" value="Register"/>
    </fieldset>
    </form>
    ';
}

public function registration_validation(         
                    $username,
                    $email,
                    $first_name,
                    $billing_company,
                    $billing_phone, 
                    $billing_email, 
                    $password, 
                    $confirm_password,
                    $accept_contact 
                    )  {

            global  $reg_errors;
            $reg_errors = new WP_Error;

            $this->errors = $reg_errors;

            if ( 4 > strlen( $username ) ) {
                $reg_errors->add( 'username_length', 'Brugernavn skal være på mindst 4 karaktere' );
            }

            if ( username_exists( $username ) )
            $reg_errors->add('user_name_exist', 'Brugernavnet '. $username .' er desværre optaget!');

            if ( ! validate_username( $username ) ) {
                $reg_errors->add( 'username_invalid', 'Ikke gyldigt brugernavn' );
            }

            /**
             * check if empty email
             */
            if ( empty( $email ) ) {
                $reg_errors->add( 'email_empty', 'E-mail er påkrævet' );
            }

            /**
             * check if not empty email and email is right format
             */
            if ( !is_email( $email ) && !empty( $email ) ) {
                $reg_errors->add( 'email_invalid', 'E-mail har ikke korrekt format' );
            }

            /**
             * check if email is in use
             */
            if ( email_exists( $email ) ) {
                $reg_errors->add( 'email_exist', 'Der er registreret en bruger på '. $email  );
            }


            /**
             * check first name not empty
             */

            if ( empty( $first_name ) ) {
                $reg_errors->add( 'first_name_empty', 'Navn er påkrævet' );
            }
            
            /**
             * check company name not empty
             */

            if ( empty( $billing_company ) ) {
                $reg_errors->add( 'billing_company_empty', 'Firmanavn er påkrævet' );
            }

             /**
             * check company phone not empty
             */

            if ( empty( $billing_phone ) ) {
                $reg_errors->add( 'billing_phone_empty', 'Telefonnummer er påkrævet' );
            }

             /**
             * check company email not empty
             */

            if ( empty( $billing_email ) ) {
                $reg_errors->add( 'billing_email_empty', 'Fakturerings E-mail er påkrævet' );
            }

            /**
             * check if not empty company email and email is right format
             */
            if ( !is_email( $billing_email ) && !empty( $billing_email ) ) {
                $reg_errors->add( 'billing_email_invalid', 'Email is not valid' );
            }

            if ( empty( $password ) ) {
                $reg_errors->add( 'password_empty', 'Password er påkrævet' );
            }

            if ( empty( $confirm_password ) ) {
                $reg_errors->add( 'confirm_password_empty', 'Bekræft Password er påkrævet' );
            }

            if ( ! empty($password) && ! empty($confirm_password) && $password !== $confirm_password  ) {
                $reg_errors->add( 'password_nomatch', 'Password og bekræft password er ikke ens' );
            }

            /*if ( is_wp_error( $reg_errors ) ) {
 
                foreach ( $reg_errors->get_error_messages() as $error ) {
                 
                    echo '<div>';

                    echo ucfirst($error) . '<br/>';
                    echo '</div>';
                     
                }
             
            }*/
}


function custom_registration_function() {
    if ( isset($_POST['submit'] ) ) {
        $this->registration_validation(
        $_POST['username'],
        $_POST['email'],
        $_POST['fname'],
        $_POST['billing_company'],
        $_POST['billing_phone'], 
        $_POST['billing_email'],         
        $_POST['password'],
        $_POST['confirm_password'],
        $_POST['accept_contact'],



        
        );
         
        // sanitize user form input
        global $username, $password, $email,  $first_name,  $billing_company, $billing_phone, $billing_email,   $password, $confirm_password, $accept_contact;
        $username   =   isset( $_POST['username'] ) ? sanitize_user( $_POST['username'] ) : '';

        $email      =   isset ( $_POST['email'] ) ? sanitize_email( $_POST['email']) : '';

        $first_name =   isset ( $_POST['fname'] ) ? sanitize_text_field( $_POST['fname']): '';

        $billing_company   =  isset(  $_POST['billing_company'] ) ?  sanitize_text_field( $_POST['billing_company']) : '';
        $billing_phone   =   isset( $_POST['billing_phone'] )  ? sanitize_text_field( $_POST['billing_phone'] ): '';
        $billing_email   =   isset( $_POST['billing_email'] ) ? sanitize_email( $_POST['billing_email'] ): '';

        $password   =   isset( $_POST['password'] ) ? esc_attr( $_POST['password'] ) : '';

        $accept_contact = isset($_POST['accept_contact']) ? 1 : 0 ;

 
        // call @function complete_registration to create the user
        // only when no WP_error is found
        $this->complete_registration(
        $username,
        $email,
        $first_name,
        $billing_company,
        $billing_phone, 
        $billing_email, 
        $password, 
        $confirm_password,
        $accept_contact

        );
    }
 
        $this->registration_form(
        $this->username,
        $this->email,
        $this->first_name,
        $this->billing_company,
        $this->billing_phone, 
        $this->billing_email, 
        $this->password, 
        $this->confirm_password,
        $this->accept_contact

        );
}


public function complete_registration() {

    $session = new Session();

    global $reg_errors, $username,  $email,  $first_name,  $billing_company, $billing_phone, $billing_email,   $password, $accept_contact;
    if ( 1 > count( $reg_errors->get_error_messages() ) ) {
        $userdata = array(
        'user_login'    =>   $username,
        'user_email'    =>   $email,
        'user_pass'     =>   $password

        );
        $user = wp_insert_user( $userdata );

        update_user_meta( $user, 'billing_company', $billing_company );
        update_user_meta( $user, 'billing_phone', $billing_phone );
        update_user_meta( $user, 'billing_email', $billing_email );
        update_user_meta( $user, 'accept_contact', $accept_contact );
        update_user_meta( $user, 'first_name', $first_name );


     //   echo 'Registration complete. Goto <a href="' . get_site_url() . '/wp-login.php">login page</a>.';   
   
    
      $this->mail_info = array(

        //'FromMail' defines who the mail is from
        
        'FromMail' => 'info@gestusnord.dk',
        'FromName' => 'Gestus Nord',
        'ToMail' => sanitize_email( $_POST['email'] ),
        'ToName' => sanitize_text_field( $_POST['fname'] ),
        'Subject' => 'Gestus Nord - link til auktionsside',
        'HtmlBody' => '<h4>Hej '.sanitize_text_field( $_POST['fname'] ).'</h4><br>Tak for din registrering.<br><br>Du kan nu deltage i Gestus Nords store auktionsjulekalender.<br><br>Find auktionerne her: <a href="https://gestusnord.dk/all-auctions/">Gestus Nord´s juleauktioner</a> <br>Direkte link til <a href="https://gestusnord.dk/wp-login.php">Login</a>',
        'PlainTextBody' => 'Tak for din registrering. Du kan nu deltage i Gestus Nords store auktionsjulekalender. Find auktionerne her: https://gestusnord.dk/all-auctions/ direkte link til login siden er https://gestusnord.dk/wp-login.php',
        'ReceiptGreating' => 'Der blev sendt en email til ' . sanitize_email( $_POST['email'] ) 
            

        );

        $session->flash('register_receipt', 'Der blev sendt en email til ' . sanitize_email( $_POST['email'] ) .' <br>Tjek evt. dit spam filter hvis du ikke har modtaget nogen E-mail');


      $this->mailsender->responsemail($this->mail_info);

 ?>
     <script> location.replace("/wp-login.php"); </script>

     <?php
        
   
    }
}


function wpse330527_add_html_content() {

    $session = new Session();
echo "<h3 style='text-align:center;margin-bottom:60px;'>" . $session->flash('register_receipt') . "</h3>";


}

 
// The callback function that will replace [book]
function custom_registration_shortcode() {
    ob_start();
    $this->custom_registration_function();
    return ob_get_clean();
}


/**
 * 
 * if (Session::exists('signin')) {

  *  echo "<h2>" . Session::flash('signin') . "</h2>";
 *}
 */



}

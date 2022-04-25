<?php

namespace Inc\Base;


use Inc\Base\BaseController;


class AuctionController extends BaseController
{

   
    

    public function register()
    {


        /*session_start();

        



        $_SESSION['auction'] = get_option('link_token');


        if (isset($_GET['auktion_token'])) {
            
            $_SESSION['get_token'] = $_GET['auktion_token'];
            

        
        }*/


        add_action('template_redirect', array($this, 'redirect_if_not_loggedin'));

        add_filter( 'login_redirect', array($this, 'redir_after_login'), 10, 3 );

        add_action( 'register_form', array($this, 'redirect_no_token' ));

        add_action( 'admin_init', array( $this, 'my_auction_settings' ));

        remove_action('woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20);

             
     }




    /***
     * 
     * 
     * 
     * 
     * 
     */

     /*------------------------------------*\
    Custom fields general settings page generate token and links for auktion page
    \*------------------------------------*/

public function my_auction_settings() {
	add_settings_section(
		'my_settings_section', // Section ID
		'Indstillinger for auktioner', // Section Title
		array( $this, 'my_section_options_callback'), // Callback
		'general' // What Page?  This makes the section show up on the General Settings Page
	);

	add_settings_field( // mobile number
		'link_token', // mobile number ID
		'Seneste Token', // Label
		array( $this ,'my_textbox_callback'), // !important - This is where the args go!
		'general', // Page it will be displayed
		'my_settings_section', // Name of our section (General Settings)
		array( // The $args
			'link_token',// Should match mobile number ID
			'text'
		)
	);

    register_setting( 'general', 'link_token', 'esc_attr' );
    





}

   public function my_section_options_callback() { // Section Callback

        echo '<table class="form-table" role="presentation"><tbody><tr><th scope="row"></th><td>';
        echo '<p><h3>Indstillinger for auktionerne, seneste link og token</h3></p>';
        echo '<p><h4>Nuværende gyldige link til log in formen er</h4></p>';
        
      
        ?>


        <?php 
        echo '<p><label for="shortLink">Link herunder er til invitationssiden, sendes sammen med Keld´s invitation.</label></p>';
        echo '<p><input type="text" id="shortLink" readonly value="https://gestusnord.dk/auktioner/?auktion_token='. get_option("link_token").'">';
        
        ?>        
        <a style="  padding:5px 10px;
                    border:solid 1px #333; 
                    background-color: #3858E9; 
                    color: #fff; 
                    border-radius: 3px;
                    cursor:pointer;" onclick="copyToClipboard('shortLink')";>Kopier link</a></p><br><br>


                    <p>
        <a style="  padding:5px 10px;
                    border:solid 1px #333; 
                    background-color: #3858E9; 
                    color: #fff; 
                    border-radius: 3px;
                    cursor:pointer;" onclick="rand_token()";>generer nyt token</a></p>
        <?php
        echo '<h2>OBS VIGTIGT, MÅ IKKE ÆNDRES HVIS EN PERIODE MED AUKTIONER ER I GANG !</h2>';

        echo '</td></tr></tbody></table>';

        ?>
        <script>

document.addEventListener("DOMContentLoaded", function(event){
  // your code here


  let shortlink = document.getElementById("shortLink");

  
  shortlink.style.width =  ((shortlink.value.length + 1) * 8) + 'px';

});

function rand_token()
{


    let token = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);


    var r = confirm("Er du sikker på du vil ændrer token. \nKAN IKKE FORTRYDES! \nNår først Gem ændringer er trykket\nDet gamle registreringslink til auktioner\nvil derfor ikke fungere mere!");
    if (r == true) {
        document.getElementById('link_token').value = token;
    } 
    
}

function copyToClipboard(element) {
        var copyText = document.getElementById(element);
        copyText.select();
        copyText.setSelectionRange(0, 99999)
        document.execCommand("copy");
        alert("Tekst er kopieret til udklipsholder");
}
        </script>
        <?php
    }
    


    public  function my_textbox_callback( $args ) {  // Textbox Callback
        $option = get_option( $args[0] );
        echo '<input type="' . $args[1] . '" id="' . $args[0] . '" name="' . $args[0] . '" value="' . $option . '" />';
    }


     /**
      * 





      */

    public function redirect_no_token()
    {

       // get_option('link_token')


       if (isset($_GET['auktion_token'])) {
            
        if( get_option('link_token') != $_GET['auktion_token']){
        
        ?>
        <script> location.replace("/wp-login.php"); </script>

        <?php
    
        }}

     /*   if ( $_SESSION['get_token'] != $_SESSION['auction'] ) {

 
            ?>
            <script> location.replace("/wp-login.php"); </script>

            <?php
            }*/
    }



    public function redirect_if_not_loggedin()
{
    $redir_page = 'all-auctions';

    $Substring = array('vare', 'shop', 'cart');

    $string = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
   

    
    $sub = $this->array_contains($string, $Substring);



    if ( !is_user_logged_in() && is_page($redir_page) || !is_user_logged_in() && $sub == true) {

        wp_redirect( wp_login_url()  ); 
            exit;
        }


        
}

        function array_contains($string, array $Substring){
            foreach($Substring as $a){
                if(stripos($string, $a) !== false) return true;
            }
            return false;
        }

    public function redir_after_login($url, $request, $user)
    {



        if (  $user && is_object( $user ) && is_a( $user, 'WP_User' ) ) {
            if ( $user->has_cap( 'Erhvervspartner' ) ) {
               $url = home_url( '/all-auctions' );
            } else {

                if( is_admin() ){

                     $url = admin_url();

                }else{

                     $url = admin_url( 'profile.php' );
                }
               
                
            }
        }
        return $url;





    }


    



}



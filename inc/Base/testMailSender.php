<?php

namespace Inc\Base;

use Inc\Tools\Session;
use Inc\Base\BaseController;

class testMailSender extends BaseController
{

    public function register()
    {

        add_shortcode( 'send_email', array($this, 'send_email')); 
    }




    public function send_email( $args )
    {


        ob_start();
        $aplicant = get_post_meta(3786 , '_gestus_applicants_key', true);


        print_r($aplicant);

        $session = new Session();

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
    
            //$session->flash('register_receipt', 'Der blev sendt en email til ' . sanitize_email( $_POST['email'] ) .' <br>Tjek evt. dit spam filter hvis du ikke har modtaget nogen E-mail');
    
    
          //$this->mailsender->responsemail($this->mail_info);

          return ob_get_clean();	 
    }
}

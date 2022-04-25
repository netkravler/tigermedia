<?php

namespace Inc\Base;



use Inc\Base\BaseController;



use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailController extends BaseController
{

    public $mail;
    public $exception;

    public function register()
    {





       // add_action('init', array($this, 'register_gestus_email_templates'));

    }

    /**
 * 
 * register applicant post type 
 */
public function register_gestus_email_templates()
{


    $labels = array(

        'name' => 'email_templates',
        'singular_name' => 'email_template',
        'menu_name' => 'Email skabeloner'
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
            //'create_posts' => false, // prevent users from creating new posts false or true
        ),
        'map_meta_cap' => true
       
        
    );

    
    register_post_type('email_templates', $args);
}


    //responsemail function 

    public function responsemail($args)
    {




        $mail = new PHPMailer(TRUE);
        $exception = new Exception();

        $mail->CharSet = 'UTF-8';
        
        $mail->Sender = $args['FromMail'];

        //email from
        $mail->setFrom( $args['FromMail'] , $args['FromName'], FALSE );
        //to 
        $mail->addAddress($args['ToMail'], $args['ToName']);

        //Address to which recipient will reply
        $mail->addReplyTo( $args['FromMail'] , "Reply");

        //Send HTML or Plain Text email
        $mail->isHTML(true);

        $mail->Subject = $args['Subject'];

        $mail->Body = $args['HtmlBody'];
        $mail->AltBody = isset ( $args['PlainTextBody'] ) ? $args['PlainTextBody'] : '';

        //CC and BCC
        /*$this->mail->addCC("cc@example.com");
        $this->mail->addBCC("bcc@example.com");*/

        try {


            $mail->send();


          //  echo isset ( $args['ReceiptGreating']) ? $args['ReceiptGreating'] : '';
        } catch (Exception $e) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }
    }

    //responsemail function for aid application refused aid
    public function refused_aid($parents, $sender, $post_id)
    {

            global $current_user;
            wp_get_current_user();
            $fname =   $current_user->user_firstname ;
            $lname = $current_user->user_lastname;

        $this->mail_info = array(

            //'FromMail' defines who the mail is from
            
            'FromMail' => $sender,
            'FromName' => 'Gestus Nord',
            'ToMail' => sanitize_email( $parents[0]['email'] ),
            'ToName' => sanitize_text_field( 'miv' ),
            'Subject' => 'Gestus Nord - angående julehjælpsansøgning 2020',
            'HtmlBody' => '<h4>Hejsa '.sanitize_text_field( $parents[0]['firstname'] .' '. $parents[0]['lastname'] ).'</h4><br>Gestus Nord har nu behandlet din julehjælps-ansøgning. Gestus Nord modtager rigtig mange ansøgninger hvert år og kan desværre ikke opfylde alle. Derfor bliver det desværre et afslag på din julehjælps-ansøgning i 2020.<br><br>På vegne af julehjælpsudvalget

            <br><br>
            Jesper Dalsgaard
            
            <br><br><a href="https://gestusnord.dk"><img src="https://gestusnord.dk/wp-content/uploads/gestusnord_logo.jpg" alt="Gestus nord logo"></a>    

            ',
            'PlainTextBody' => nl2br('Hejsa '.sanitize_text_field( $parents[0]['firstname'] .' - '. $parents[0]['lastname'] ).'\n\nGestus Nord har nu behandlet din julehjælps-ansøgning. Gestus Nord modtager rigtig mange ansøgninger hvert år og kan desværre ikke opfylde alle. Derfor bliver det desværre et afslag på din julehjælps-ansøgning i 2020.\n\nPå vegne af julehjælpsudvalget

            \n\nJesper Dalsgaard
            

            \n\n'),
            'ReceiptGreating' => 'Der blev sendt en email til ' . sanitize_email( 'miv@netkravler.com' ) 
                
    
            );
             
        
        
                $this->responsemail($this->mail_info);

    }

    //responsemail function for aid application refused aid
    public function granted_aid($parents, $sender, $post_id)
    {

        global $current_user;
        wp_get_current_user();
        $fname =   $current_user->user_firstname ;
        $lname = $current_user->user_lastname;

    //'FromMail' defines who the mail is from
    $afhentning = '- Afhentning mellem kl. 11.00-12.00';
            
    $this->mail_info = array(

        //'FromMail' defines who the mail is from
        
        'FromMail' => $sender,
        'FromName' => 'Gestus Nord',
        'ToMail' => sanitize_email( $parents[0]['email'] ),
        'ToName' => sanitize_text_field( 'miv' ),
        'Subject' => 'Gestus Nord - angående julehjælpsansøgning 2020',
        'HtmlBody' => '<h4>Hejsa '.sanitize_text_field( $parents[0]['firstname'] .' '. $parents[0]['lastname'] ).'</h4>
        

        <br>
        <p>Gestus Nord har nu behandlet din julehjælps-ansøgning. Gestus Nord kan med glæde meddele, at du er udvalgt til at få Gestus Nords julepakke 2020. Du kan afhente din julepakke, bestående af mad til juleaften, søndag den 20/12. HUSK denne mail og ID (sygesikringsbevis, kørekort, pas eller lignende).</p>

 

        <p>Gestus Nord har i de sidste mange år haft en stor juletræsfest, hvor selve uddelingen har foregået. Det er desværre ikke muligt i år grundet den store pandemi COVID-19. Derfor vil vi bede dig om, at vælge hvilket uddelingssted du ønsker at afhente din Gestus Nord julepakke ved.</p>
        
         
        
        <p><h5>Du har følgende muligheder:</h5></p>
        
        <ul>
        <li>Kærvej 1, 9900 Frederikshavn - P-plads ved Frederikshavn Gymnasium og HF '.$afhentning.'</li>
        
        <li>Skolevangen 25, 9800 Hjørring – P-plads ved Hjørring Gymnasium og HF Kursus '.$afhentning.'</li>
        
        <li>Skovbrynet 9 9690 Fjerritslev – P-plads ved Fjerritslev Gymnasium '.$afhentning.'</li>
        
        <li>Studievej 14, 9400 Nørresundby – P-plads ved Nørresundby Gymnasium og HF '.$afhentning.'</li>
        
        <li>Fyrkildevej 7, 9220 Aalborg Øst – P-plads ved Sundheds- og kvartershuset '.$afhentning.'</li>
        
        <li>Hasserisvej 300, 9000 Aalborg – P-plads ved Hasseris Gymnasium & IB '.$afhentning.'</li>
        
        <li>Præstevej, 9530 Støvring - Samkørselsplads ved Støvring Syd overfor OK tankstation '.$afhentning.'</li>
        </ul>
         
        
        <p>Vi opfordrer alle til at kun én person afhenter julepakken, bære mundbind og holde afstand.</p>
        
         <p> </p>
        
        <p>HUSK at besvare denne mail med uddelingssted. Vi glæder os til at se jer.</p>
        
         
        
        <p>På vegne af julehjælpsudvalget</p>
        
        <p>Jesper Dalsgaard</p>
        
        
        
        <br><br><a href="https://gestusnord.dk"><img src="https://gestusnord.dk/wp-content/uploads/gestusnord_logo.jpg" alt="Gestus nord logo"></a>    



        ',
        'PlainTextBody' => nl2br(
            
        'Hejsa '.sanitize_text_field( $parents[0]['firstname'] .' '. $parents[0]['lastname'] ).'
                
        Gestus Nord har nu behandlet din julehjælps-ansøgning. Gestus Nord kan med glæde meddele, at du er udvalgt til at få Gestus Nords julepakke 2020. Du kan afhente din julepakke, bestående af mad til juleaften, søndag den 20/12. HUSK denne mail og ID (sygesikringsbevis, kørekort, pas eller lignende).

        

        Gestus Nord har i de sidste mange år haft en stor juletræsfest, hvor selve uddelingen har foregået. Det er desværre ikke muligt i år grundet den store pandemi COVID-19. Derfor vil vi bede dig om, at vælge hvilket uddelingssted du ønsker at afhente din Gestus Nord julepakke ved.

        

        Du har følgende muligheder:

        Kærvej 1, 9900 Frederikshavn - P-plads ved Frederikshavn Gymnasium og HF

        Skolevangen 25, 9800 Hjørring – P-plads ved Hjørring Gymnasium og HF Kursus

        Skovbrynet 9 9690 Fjerritslev – P-plads ved Fjerritslev Gymnasium

        Studievej 14, 9400 Nørresundby – P-plads ved Nørresundby Gymnasium og HF

        Fyrkildevej 7, 9220 Aalborg Øst – P-plads ved Sundheds- og kvartershuset

        Hasserisvej 300, 9000 Aalborg – P-plads ved Hasseris Gymnasium & IB

        Præstevej, 9530 Støvring - Samkørselsplads ved Støvring Syd overfor OK tankstation

        

        Vi opfordrer alle til at kun én person afhenter julepakken, bære mundbind og holde afstand.

        

        HUSK at besvare denne mail med uddelingssted. Vi glæder os til at se jer.

        

        På vegne af julehjælpsudvalget

        Jesper Dalsgaard


        
        
'),
        'ReceiptGreating' => 'Der blev sendt en email til ' . sanitize_email( 'miv@netkravler.com' ) 
            

        );

        $this->responsemail($this->mail_info);
    }



    //responsemail function for erhvervspartnerform

    //responsemail function for applicant forms


}

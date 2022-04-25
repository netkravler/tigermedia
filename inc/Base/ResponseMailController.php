<?php

namespace Inc\Base;

use Inc\Api\SettingsApi;
use Inc\Base\BaseController;
use Inc\Api\Callbacks\AdminCallbacks;
use Inc\Api\Callbacks\FormFieldCallback;


class ResponseMailController extends BaseController
{


    public $settings;

    public $callbacks;    

    public $form_field_callback; 

    public function register()
    {



        if ( !$this->activated('gestus_email_manager')) return;


        $this->settings = new SettingsApi();

        $this->callbacks = new AdminCallbacks();



        $this->form_field_callback = new FormFieldCallback();
        


        $this->setSubpages();

        $this->setSettings();

        $this->setSections();

        $this->setFields();


        $this->settings->addSubPages( $this->subpages )->register();




        add_filter( 'wp_mail_content_type', array($this, 'wpdocs_set_html_mail_content_type' ));

        remove_filter( 'wp_mail_content_type', array( $this, 'wpdocs_set_html_mail_content_type' ));
    }


    public function sendmail($to , $subject, $body){

       // $to = $to.' , info@gestusnord.dk';
        $subject = $subject;
        $body = $body;
         
        wp_mail( $to, $subject, $body );         

     }    

    public function wpdocs_set_html_mail_content_type() {
        return 'text/html';
    }



    public function setSubpages()
    {

        $this->subpages = [
            [
 
             'parent_slug' => 'gestus_nord_admin',
             'page_title' => 'E-mail manager',
             'menu_title' => 'E-mail Manager', 
             'capability' => 'manage_options',
             'menu_slug' => 'email_manager',
             'callback' => array( $this->callbacks, 'emailManager')
 
            ]
     ];

    }

    public function setSettings(){


        $args = array(

             array(
            'option_group' => 'gestus_email_settings',
            'option_name' => 'gestus_email_manager',
            'callback' => array($this->form_field_callback  , 'Sanitize')
             ),array(
               'option_group' => 'choose_mail_settings',
               'option_name' => 'use_mails_on_posttype',
               'callback' => array($this->form_field_callback  , 'Sanitize')
               ),array(
                'option_group' => 'choose_mail_type',
                'option_name' => 'set_email_type',
                'callback' => array($this->form_field_callback  , 'Sanitize')
                )

        );


        

        

        $this->settings->setSettings( $args );
    }


    public function setSections(){


        $args = array(

            array(

                'id' => 'gestus_email_settings', // unik id
                'title' => 'Opret e-mail skabeloner',
               // 'callback' => array($this->callbacks , 'sectionsOptionsGroup_settings') ,
                'page' => 'gestus_email_manager'

            ),
            array(

                'id' => 'choose_mail_settings', // unik id
                'title' => 'Vælg sider der skal acceptere emails',
               // 'callback' => array($this->callbacks , 'sectionsOptionsGroup_settings') ,
                'page' => 'use_mails_on_posttype'

            ),
            array(

                'id' => 'choose_mail_type', // unik id
                'title' => 'Opret type af email.',
               // 'callback' => array($this->callbacks , 'sectionsOptionsGroup_settings') ,
                'page' => 'set_email_type'

            )
        );

        $this->settings->setSections( $args );
    }



    public function setFields(){


        $args  = array(
                
            array(

                'id' => 'emails',
                'title' => 'E-mail titel "unikt navn"',
                'callback' => array($this->form_field_callback , 'textField') ,
                'page' => 'gestus_email_manager', //menu slug for the page this field is targeting
                'section' => 'gestus_email_settings', //id for the section this field is targeting
                'args' => array(
                    'option_name' => 'gestus_email_manager' ,//must match this field page name !!!
                    'label_for' => 'emails',// must be the same as this field id
                    'class' => '',
                    'required' => 'required',
                    'field_type' => 'text',
                    'autocomplete' => $this->RandomString(10),
                    'readonly' => '',
                    'placeholder' => 'E-mail titel "unikt navn"'
                    
                )),
                     
                array(

                    'id' => 'email_subject',
                    'title' => 'Emne felt indhold',
                    'callback' => array($this->form_field_callback , 'textField') ,
                    'page' => 'gestus_email_manager', //menu slug for the page this field is targeting
                    'section' => 'gestus_email_settings', //id for the section this field is targeting
                    'args' => array(
                        'option_name' => 'gestus_email_manager' ,//must match this field page name !!!
                        'label_for' => 'email_subject',// must be the same as this field id
                        'class' => '',
                        'required' => 'required',
                        'field_type' => 'text',
                        'autocomplete' => $this->RandomString(10),
                        'placeholder' => 'Emne felt indhold'
                    )),
                
                array(

                    'id' => 'gestus_reciever',
                    'title' => 'Hvem i Gestus skal modtage denne kvittering',
                    'callback' => array($this->form_field_callback , 'textField') ,
                    'page' => 'gestus_email_manager', //menu slug for the page this field is targeting
                    'section' => 'gestus_email_settings', //id for the section this field is targeting
                    'args' => array(
                        'option_name' => 'gestus_email_manager' ,//must match this field page name !!!
                        'label_for' => 'gestus_reciever',// must be the same as this field id
                        'class' => '',
                        'required' => 'required',
                        'field_type' => 'text',
                        'autocomplete' => $this->RandomString(10),
                        'placeholder' => 'Hvilken email skal der sendes besked til '
                    )), 
                    array(

                        'id' => 'post_types',
                        'title' => '',
                        'callback' => array($this->form_field_callback , 'textField') ,
                        'page' => 'gestus_email_manager', //menu slug for the page this field is targeting
                        'section' => 'gestus_email_settings', //id for the section this field is targeting
                        'args' => array(
                            'option_name' => 'gestus_email_manager' ,//must match this field page name !!!
                            'label_for' => 'post_types',// must be the same as this field id
                            'class' => 'hide',
                            'required' => 'required',
                            'field_type' => 'hidden',
                            'autocomplete' => $this->RandomString(10),
                            'value' => isset($_POST['send_posttype']) ? $_POST['send_posttype'] : ''
                        ))
                        , 
 
                        array(

                            'id' => 'post_id',
                            'title' => '',
                            'callback' => array($this->form_field_callback , 'textField') ,
                            'page' => 'gestus_email_manager', //menu slug for the page this field is targeting
                            'section' => 'gestus_email_settings', //id for the section this field is targeting
                            'args' => array(
                                'option_name' => 'gestus_email_manager' ,//must match this field page name !!!
                                'label_for' => 'post_id',// must be the same as this field id
                                'class' => 'hide',
                                'required' => 'required',
                                'field_type' => 'hidden',
                                'value' => isset($_POST['post_id']) ? $_POST['post_id'] : ''
                            ))
                            ,

                    array(

                        'id' => 'mail_type',
                        'title' => '',
                        'callback' => array($this->form_field_callback , 'textField') ,
                        'page' => 'gestus_email_manager', //menu slug for the page this field is targeting
                        'section' => 'gestus_email_settings', //id for the section this field is targeting
                        'args' => array(
                            'option_name' => 'gestus_email_manager' ,//must match this field page name !!!
                            'label_for' => 'mail_type',// must be the same as this field id
                            'class' => 'hide',
                            'required' => 'required',
                            'field_type' => 'hidden',
                            'autocomplete' => $this->RandomString(10),
                            'value' => isset($_POST["mailtype"]) ?$_POST["mailtype"].'['. $_POST["send_posttype"] .']' : ''
                        )),   
                        
                    array(
    
                        'id' => 'chooseable',
                        'title' => 'Set som aktiv',
                        'callback' => array($this->form_field_callback , 'checkboxField') ,
                        'page' => 'gestus_email_manager', //menu slug for the page this field is targeting
                        'section' => 'gestus_email_settings', //id for the section this field is targeting
                        'args' => array(
                            'option_name' => 'gestus_email_manager' ,//must match this field page name !!!
                            'label_for' => 'chooseable',// must be the same as this field id                       
                            'value' => '1',
                            'class' => 'ui-toggle'
                            
                        )),
                        
                        array(
                            'id' => 'sendResponseNow',
                            'title' => 'Send denne øjeblikkeligt ved ansøgning',
                            'callback' => array($this->form_field_callback , 'textField') ,
                            'page' => 'gestus_email_manager', //menu slug for the page this field is targeting
                            'section' => 'gestus_email_settings', //id for the section this field is targeting
                            'args' => array(
                                'option_name' => 'gestus_email_manager' ,//must match this field page name !!!
                                'label_for' => 'sendResponseNow',
                                'class' => 'ui-toggle hide',
                                'field_type' => 'hidden',
                               'value' => isset($_POST['sendResponseNow']) ? $_POST['sendResponseNow'] : ''
                            )
                            ),
                    array(

                        'id' => 'body',
                        'title' => 'Email indhold',
                        'callback' => array($this->form_field_callback , 'textField') ,
                        'page' => 'gestus_email_manager', //menu slug for the page this field is targeting
                        'section' => 'gestus_email_settings', //id for the section this field is targeting
                        'args' => array(
                            'option_name' => 'gestus_email_manager' ,//must match this field page name !!!
                            'label_for' => 'body',// must be the same as this field id
                            'class' => '',
                            'required' => 'required',
                            'field_type' => 'textarea',
                            'autocomplete' => $this->RandomString(10),
                            'placeholder' => 'Angiv indhold af den ønskede email'
                        ))
                        
                        ,
                        
                        array(
    
                            'id' => 'post_type',
                            'title' => 'Email indhold',
                            'callback' => array($this->form_field_callback , 'checkboxPostTypesField') ,
                            'page' => 'use_mails_on_posttype', //menu slug for the page this field is targeting
                            'section' => 'choose_mail_settings', //id for the section this field is targeting
                            'args' => array(
                                'option_name' => 'use_mails_on_posttype' ,//must match this field page name !!!
                                'label_for' => 'post_type',// must be the same as this field id
                                
                                'class' => 'ui-toggle'
                            )), 
                        
                        array(
                            'id' => 'email_type',
                            'title' => 'Navn på type af email',
                            'callback' => array($this->form_field_callback , 'textField') ,
                            'page' => 'set_email_type', //menu slug for the page this field is targeting
                            'section' => 'choose_mail_type', //id for the section this field is targeting
                            'args' => array(
                                'option_name' => 'set_email_type' ,//must match this field page name !!!
                                'label_for' => 'email_type',
                                'class' => 'ui-toggle',
                                'field_type' => 'text',
                                'readonly' => '',
                                'placeholder' => 'Giv denne type et navn'
                            )
                        ), 
                        
                        array(
                            'id' => 'sendResponseNow',
                            'title' => 'Send denne øjeblikkeligt ved ansøgning',
                            'callback' => array($this->form_field_callback , 'checkboxField') ,
                            'page' => 'set_email_type', //menu slug for the page this field is targeting
                            'section' => 'choose_mail_type', //id for the section this field is targeting
                            'args' => array(
                                'option_name' => 'set_email_type' ,//must match this field page name !!!
                                'label_for' => 'sendResponseNow',
                                'class' => 'ui-toggle',
                                'field_type' => 'checkbox',
                                'readonly' => ''
                            )
                        ), 
                        
                        
                        array(
                            'id' => 'individual_types',
                            'title' => 'Brug på individuelle posts, undlad afkrydsning hvis det skal bruges på alle af samme type',
                            'callback' => array($this->form_field_callback , 'checkboxField') ,
                            'page' => 'set_email_type', //menu slug for the page this field is targeting
                            'section' => 'choose_mail_type', //id for the section this field is targeting
                            'args' => array(
                                'option_name' => 'set_email_type' ,//must match this field page name !!!
                                'label_for' => 'individual_types',
                                'class' => 'ui-toggle hide_pages',
                                'field_type' => 'checkbox',
                                'readonly' => ''
                            )
                        ), 
                        
                        array(
                            'id' => 'idividual_pages',
                            'title' => 'Vælg siden du vil lave denne type af email til',
                            'callback' => array($this->form_field_callback , 'drop_down') ,
                            'page' => 'set_email_type', //menu slug for the page this field is targeting
                            'section' => 'choose_mail_type', //id for the section this field is targeting
                            'args' => array(
                                'option_name' => 'set_email_type' ,//must match this field page name !!!
                                'label_for' => 'idividual_pages',
                                'class' => 'individual_pages hide',
                                'field_type' => 'checkbox',
                                'post_type' => 'gestus_aid',
                                'select_type' => 'pages',
                                'readonly' => ''
                            )
                        )
                        ,
                        array(
                            'id' => 'email_description',
                            'title' => 'Beskrivelse',
                            'callback' => array($this->form_field_callback , 'textField') ,
                            'page' => 'set_email_type', //menu slug for the page this field is targeting
                            'section' => 'choose_mail_type', //id for the section this field is targeting
                            'args' => array(
                                'option_name' => 'set_email_type' ,//must match this field page name !!!
                                'label_for' => 'email_description',
                                'class' => 'ui-toggle',
                                'field_type' => 'textarea',
                                'placeholder' => 'Beskriv hvad denne email type skal bruge til'
                            )
                        )


            );
    

        $this->settings->setFields( $args );
    }



    public function Find_Mail_Type($option_key, $posttype, $emailtype, $options)
    {

        

        $accepted_posttypes = $posttype[array_key_first($posttype)]['post_type'];
      
        $loops = get_option($option_key);
        
        //var_dump($posttype);
        //echo '<pre>';
        // var_dump($option_key);
        //var_dump($emailtype);

       // print_r($options[$emailtype]['idividual_pages']);

        
        //var_dump($loops);
        //var_dump($accepted_posttypes);

        //echo '</pre>';

        $selector_id = $this->RandomString(8);

       ?>

       <?php if(!($options[$emailtype]['idividual_pages']) != 0 ){ ?>
       <select name="send_posttype"  class="inline" id="post_type_dropdown<?php echo $selector_id?>" required="required">
      <?php echo  '<option >Vælg type du vil lave skabelon for</option>';

            foreach($accepted_posttypes as $type => $value){

                var_dump($value);

                if( isset($loops) &&  $this->find_val($loops , 'mail_type' , $emailtype.'['. $type .']')){


                }
                else


                {

                    $send_posttype = $type == 'applicants' ? 'Ansøgere' : $type;

                    if ( $type !='gestus_aid' )
                    {

                        echo  '<option value="'. $type .'"> Lav skabelon for type ' . $send_posttype . ' </option>';

                    }
                   

                }
        
            }   
      
            echo ' </select>';

        }else{
?>
            <input type="hidden" name="send_posttype" id="post_type_dropdown<?php echo $selector_id?>" value="applicants">

                    <?php echo get_the_title($options[$emailtype]['idividual_pages'])?>

            <?php


        }

            ?>
            <input type="hidden" name="mail_title" id="mail_title<?php echo $selector_id?>" value="<?php echo get_the_title($options[$emailtype]['idividual_pages'])?>">
  
            <?php

            echo'<input type="hidden" name="sendResponseNow" value="'. $options[$emailtype]['sendResponseNow']. '">';
            echo'<input type="hidden" name="mailtype" value="'.$emailtype.'">';
            echo'<input type="hidden" name="post_id" value="'.$options[$emailtype]['idividual_pages'].'">';
            echo'<input type="hidden" name="manager" value="gestus_email_manager">';
            echo'<input type="hidden" id="selected_template_type'.$selector_id.'" name="accepted_posttype" value="">';
    submit_button('opret', 'primary  inline', 'submit_template'.$selector_id, false /*, array('onclick' => 'createCookie(`post_type_dropdown'.  $selector_id .'` , `mail_title'.  $selector_id .'` ,  "'. $emailtype .'")')*/);

            

            ?>


            <?php


    }



}

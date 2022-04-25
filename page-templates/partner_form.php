<?php 

/*



        privat formular

        (Opstillet som formular)
        Navn:
        Adresse:
        Postnr./by:
        Telefon-nr.:
        E-mail:
        GDPR:


*/ ?>

<form  id="primaryPostForm" class="aid_form form wp-block-column" action="#" method="POST" enctype="multipart/form-data" data-url="<?php echo admin_url('admin-ajax.php'); ?>" novalidate>


<?php

$form_elem = array(

    //full name
    array('icon' =>'<i class="fa fa-user"></i>','type' => 'text', 'label' => 'Navn' ,  'name' => 'name', 'placeholder' => 'Navn' , 'required' => 'required', 'data_type' => 'not_empty', 'error_empty' => 'Navn er påkrævet', 'error_wrong' => '', 'script' =>''),
    //adress
    array('icon' =>'<i class="fa fa-address-card"></i>','type' => 'text', 'label' => 'Adresse' , 'name' => 'adresse', 'placeholder' => 'Adresse' , 'required' => 'required', 'data_type' => 'not_empty', 'error_empty' => 'Adresse er påkrævet', 'error_wrong' => '', 'script' =>''),
    //Postnummer
    array('icon' =>'<i class="fa fa-map-pin"></i>','type' => 'text', 'label' => 'Postnummer' , 'name' => 'postalcode', 'placeholder' => 'Postnummer' , 'required' => 'required', 'data_type' => 'postnummer', 'error_empty' => 'Postnummer er påkrævet', 'error_wrong' => 'Forkert formateret postnummer', 'script' => "onchange='ziplistener(this.value, `city` )'"),
    //city
    array('icon' =>'<i class="fa fa-building"></i>','type' => 'text', 'label' => 'By' , 'name' => 'city', 'placeholder' => 'By' , 'required' => 'required', 'data_type' => 'not_empty', 'error_empty' => 'By er påkrævet', 'error_wrong' => '', 'script' =>''),
    //phonenumber
    array('icon' =>'<i class="fa fa-phone-square"></i>','type' => 'text', 'label' => 'Telefonnummer' , 'name' => 'phonenumber', 'placeholder' => 'Telefonnummer' , 'required' => 'required', 'data_type' => 'phonenumber', 'error_empty' => 'Telefonnummer er påkrævet', 'error_wrong' => '', 'script' =>''),
    //company_email
    array('icon' =>'<i class="fa fa-envelope-square"></i>','type' => 'text', 'label' => 'E-mail' , 'name' => 'email', 'placeholder' => 'Din E-mail' , 'required' => 'required', 'data_type' => 'email', 'error_empty' => 'E-mail er påkrævet', 'error_wrong' => 'Forkert format på E-mail', 'script' =>''),

);

?>


<?php 

use Inc\Tools\Formbuilder;

$builder = new Formbuilder();

$builder->form_builder($form_elem);

?>


<div class="field-container">
    <label for="gender_man">Mand<input type="radio" id="gender_man" name="gender" value="man" class="field-input" data-type="checkbox" required="required"></label>
    <label for="gender_woman">Kvinde<input type="radio" id="gender_woman" name="gender" value="woman" class="field-input" data-type="checkbox"></label>
    <small class="field-msg_wrong error" data-error="wrongformatGender">Angiv køn </small></div>



        <?php wp_nonce_field( 'post_nonce', 'post_nonce_field' ); ?>

        <div class="field-container">
        <label for="mobilepay"><input type="checkbox"   id="mobilepay"  name="mobilepay"  > Jeg har overført 100 kr. på Mobilepay. </label>

      

        </div>

        <div class="field-container">
        <label for="reminder"><input type="checkbox"   data-type="checkbox" id="reminder"  name="reminder"  required="required"> Jeg accepterer at Gestus Nord sender mig en opkrævning på 100 kr. igen i næste kalenderår. </label>

        


        </div>




        <div class="field-container">
        <label for="gdpr"><input type="checkbox" class="field-input"  data-type="checkbox" id="gdpr"  name="gdpr"  required="required"> Gestus Nord må gemme ovenstående data. </label>

        <small class="field-msg_wrong error" data-error="wrongformatGdpr">Det er påkrævet at vi beder om din accept</small>


        </div>


        <input type="hidden" name="aid_type" value="<?php echo get_the_title() ?>">
        <input type="hidden" name="action" value="submit_partner">
	    <input type="hidden" name="user_ip" value="<?php echo $_SERVER['REMOTE_ADDR']?>">

        <!--<button type="submit" class="button submit btn btn--main_red fontcolor--white" id="submit" name="submit"   style="display:block">Send ansøgning</button>-->

        
        <div class="field-container">
		<div>
            <button type="submit"  class="btn btn--main_red fontcolor--white " >Send dine oplysninger</button>
        </div>
		<small class="field-msg js-form-submission">Er ved at gemme dine oplysninger, et øjeblik&hellip;</small>
		<small class="field-msg success js-form-success">Dine oplysninger er nu registreret! <br> Vi vender tilbage til dig</small>
		<small class="field-msg error js-form-error">Der var et problem med at sende formen prøv igen!</small>
    </div>
    
</form>
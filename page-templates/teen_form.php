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

    //first name
    array('icon' =>'<i class="fa fa-user"></i>','type' => 'text', 'label' => 'Teenagers fornavn' ,  'name' => 'fname', 'placeholder' => 'Teenagers fornavn' , 'required' => 'required', 'data_type' => 'not_empty', 'error_empty' => 'Teenagers fornavn er påkrævet', 'error_wrong' => '', 'script' =>''),
   
    //last name
    array('icon' =>'<i class="fa fa-user"></i>','type' => 'text', 'label' => 'Teenagers efternavn' ,  'name' => 'lname', 'placeholder' => 'Teenagers efternavn' , 'required' => 'required', 'data_type' => 'not_empty', 'error_empty' => 'Teenagers efternavn er påkrævet', 'error_wrong' => '', 'script' =>''),

    //adress
    array('icon' =>'<i class="fa fa-address-card"></i>','type' => 'text', 'label' => 'Teenagers adresse' , 'name' => 'adresse', 'placeholder' => 'Teenagers adresse' , 'required' => 'required', 'data_type' => 'not_empty', 'error_empty' => 'Teenagers adresse er påkrævet', 'error_wrong' => '', 'script' =>''),
    //Postnummer
    array('icon' =>'<i class="fa fa-map-pin"></i>','type' => 'text', 'label' => 'Postnummer' , 'name' => 'postalcode', 'placeholder' => 'Postnummer' , 'required' => 'required', 'data_type' => 'postnummer', 'error_empty' => 'Postnummer er påkrævet', 'error_wrong' => 'Forkert formateret postnummer', 'script' => "onchange='ziplistener(this.value, `city` )'"),
    //city
    array('icon' =>'<i class="fa fa-building"></i>','type' => 'text', 'label' => 'By' , 'name' => 'city', 'placeholder' => 'By' , 'required' => 'required', 'data_type' => 'not_empty', 'error_empty' => 'By er påkrævet', 'error_wrong' => '', 'script' =>''),
    //phonenumber
    array('icon' =>'<i class="fa fa-phone-square"></i>','type' => 'text', 'label' => 'Teenagers mobilnummer' , 'name' => 'phonenumber', 'placeholder' => 'Teenagers mobilnummer' , 'required' => 'required', 'data_type' => 'phonenumber', 'error_empty' => 'Telefonnummer er påkrævet', 'error_wrong' => '', 'script' =>''),
    
    //company_email
    array('icon' =>'<i class="fa fa-envelope-square"></i>','type' => 'text', 'label' => 'Teenagers E-mail' , 'name' => 'email', 'placeholder' => 'Teenagers E-mail' , 'required' => 'required', 'data_type' => 'email', 'error_empty' => 'E-mail er påkrævet', 'error_wrong' => 'Forkert format på E-mail', 'script' =>''),

    //company_email
    array('icon' =>'<i class="fa fa-users"></i>','type' => 'text', 'label' => 'Teenagers klassetrin' , 'name' => 'klasse', 'placeholder' => 'Teenagers klassetrin' , 'required' => 'required', 'data_type' => 'not_empty', 'error_empty' => 'Teenagers klasse er påkrævet', 'error_wrong' => '', 'script' =>''),

    //company_email
    array('icon' =>'<i class="fa fa-home"></i>','type' => 'text', 'label' => 'Teenagers skole' , 'name' => 'skole', 'placeholder' => 'Teenagers skole' , 'required' => 'required', 'data_type' => 'not_empty', 'error_empty' => 'Din skole er påkrævet', 'error_wrong' => '', 'script' =>''),


    //first name
    array('icon' =>'<i class="fa fa-user"></i>','type' => 'text', 'label' => 'Forælder fornavn' ,  'name' => 'pfname', 'placeholder' => 'Forælder fornavn' , 'required' => 'required', 'data_type' => 'not_empty', 'error_empty' => 'Forælder fornavn er påkrævet', 'error_wrong' => '', 'script' =>''),
   
    //last name
    array('icon' =>'<i class="fa fa-user"></i>','type' => 'text', 'label' => 'Forælder efternavn' ,  'name' => 'plname', 'placeholder' => 'Forælder efternavn' , 'required' => 'required', 'data_type' => 'not_empty', 'error_empty' => 'Forælder efternavn er påkrævet', 'error_wrong' => '', 'script' =>''),


    //company_email
    array('icon' =>'<i class="fa fa-envelope-square"></i>','type' => 'text', 'label' => 'Forælders E-mail' , 'name' => 'pemail', 'placeholder' => 'Forælders E-mail' , 'required' => 'required', 'data_type' => 'email', 'error_empty' => 'Forælders E-mail er påkrævet', 'error_wrong' => 'Forkert format på E-mail', 'script' =>''),

    //phonenumber
    array('icon' =>'<i class="fa fa-phone-square"></i>','type' => 'text', 'label' => 'Forælders mobilnummer' , 'name' => 'pphonenumber', 'placeholder' => 'Forælders mobilnummer' , 'required' => 'required', 'data_type' => 'phonenumber', 'error_empty' => 'Forælders mobilnummer er påkrævet', 'error_wrong' => '', 'script' =>''),
    

);

?>

   <!----> <div class="field-container">
       <h5>Hvilket køn er teenageren </h5>
    <label for="gender_woman">Ung kvinde<input type="radio" id="gender_woman" name="gender" value="woman" class="field-input" data-type="checkbox"></label>   
    <label for="gender_man">Ung mand<input type="radio" id="gender_man" name="gender" value="man" class="field-input" data-type="checkbox" required="required"></label>
   <small class="field-msg_wrong error" data-error="wrongformatGender">Angiv køn </small>
    </div>

    <div class="field-container ">
        <label for="age">Teenagers Fødselsdato eks. 13-10-2009</label>
        <div class="icon_container">
            <input type="date" id="age" name="age" class="field-input" required="required" data-type="not_empty">
            <i class="fa fa-calendar"></i>
        </div>
        <small class="field-msg error" data-error="invalidAge">Angiv Fødselsdato</small></div>

<?php 

use Inc\Tools\Formbuilder;

$builder = new Formbuilder();

$builder->form_builder($form_elem);

?>





        <?php wp_nonce_field( 'post_nonce', 'post_nonce_field' ); ?>


        <div class="field-container">
        <label for="joinreason">Begrund dit ønske om at deltage i Gestus Teen</label>
        <textarea name="joinreason" id="joinreason" required="required" class="field-input" data-type="nonum_text" cols="30" rows="10"></textarea>
        <small class="field-msg error" data-error="invalidJoinreason">Beskriv hvorfor du vil deltage i gestus teen</small>
        </div>




        <div class="field-container">
        <label for="gdpr"><input type="checkbox" class="field-input"  data-type="checkbox" id="gdpr"  name="gdpr"  required="required"> Gestus Nord må gemme ovenstående data. </label>

        <small class="field-msg_wrong error" data-error="wrongformatGdpr">Det er påkrævet at vi beder om din accept</small>


        </div>



        <input type="hidden" name="action" value="submit_teen">
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
<?php




global $post;



$data = get_post_meta( $post->ID, '_gestus_volunteer_form_key', true );



?>
               
<link rel="stylesheet" href="<?php echo plugin_dir_url( __DIR__ )?>assets/forms.css" type="text/css" media="all" />
   
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
    array('icon' =>'<i class="fa fa-envelope-square"></i>','type' => 'text', 'label' => 'E-mail' , 'name' => 'email', 'placeholder' => 'E-mail' , 'required' => 'required', 'data_type' => 'email', 'error_empty' => 'Din email er påkrævet', 'error_wrong' => 'Forkert format på E-mail', 'script' =>''),

    //company_email
    array('icon' =>'<i class="fa fa-calendar"></i>','type' => 'date', 'label' => 'Fødselsdato ' , 'name' => 'age', 'placeholder' => '' , 'required' => 'required', 'data_type' => 'not_empty', 'error_empty' => 'Fødselsdato er påkrævet', 'error_wrong' => '', 'script' =>''),


);

?>

<?php 

use Inc\Tools\Formbuilder;

$builder = new Formbuilder();

$builder->form_builder($form_elem);

?>

    <fieldset>
        <legend>Skriv et par ord om hvorfor du vil være frivillig :-)</legend>

    <div id="comment"><div class="field-container"><textarea id="comments" name="comments" required="required" placeholder="Skriv her" class="field-input" data-type="nonum_text" rows="6"></textarea>
    <small class="field-msg error" data-error="invalidComments">Du mangler at skrive lidt om dig selv</small></div></div>
        </fieldset>


<div class="field-container">
    <label for="gender_man">Mand<input type="radio" id="gender_man" name="gender" value="man" class="field-input" data-type="checkbox" ></label>
    <label for="gender_woman">Kvinde<input type="radio" id="gender_woman" name="gender" value="woman" class="field-input" data-type="checkbox"></label>
    <small class="field-msg_wrong error" data-error="wrongformatGender">Angiv køn </small></div>

        <input type="hidden" name="action" value="submit_gestus_volunteer">
        <input type="hidden" name="user_ip" value="<?php echo $_SERVER['REMOTE_ADDR']?>">

 <?php
 
 $output = get_option('gestus_settings_manager'); 

?>

  
<div class="field-container ">

    <h6>Hvilke aktiviteter ønsker du at hjælpe i ?</h6>
    
    <fieldset class="activities">
      <legend>Kræver børneattest</legend>  
<?php


foreach($output as $item => $key){


    if (isset($key['attest'])){?>


            <div class="label" ><?php echo $item ?> 

  
            <div class="ui-toggle "><input type="checkbox" class="ui-toggle" id="<?php echo $item?>" value="<?php echo $item?>" name="attest[]" class="field-input"   data-type="checkbox"><label for="<?php echo $item ?>" style="margin-left: 0;"><div></div></label></div>
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
  
  <div class="label" ><?php echo $item ?> 

  
<div class="ui-toggle "><input type="checkbox" class="ui-toggle" id="<?php echo $item?>" value="<?php echo $item?>" name="noattest[]" class="field-input"   data-type="checkbox"><label for="<?php echo $item ?>" style="margin-left: 0;"><div></div></label></div>
</div>
  
     <?php }
  }?>
</div>
<small class="field-msg_wrong error" data-error="wrongformatNoattest">Fødselsdato er påkrævet</small>
</fieldset>
<div class="field-container">
		<label for="gdpr"><input type="checkbox" class="field-input" data-type="checkbox" id="gdpr" name="gdpr" required="required"> Gestus Nord må gemme ovenstående data. </label>

		<small class="field-msg_wrong error" data-error="wrongformatGdpr">Det er påkrævet at vi beder om din accept</small>

		
	</div>
        
<div class="field-container">
		<div>
            <button type="submit"  class="btn btn--main_red fontcolor--white " >Bliv frivillig</button>
        </div>
		<small class="field-msg js-form-submission">Er ved at sende, et øjeblik&hellip;</small>
		<small class="field-msg success js-form-success">Din tilmelding er sendt tak!</small>
		<small class="field-msg error js-form-error">Der var et problem med at sende formen prøv igen!</small>
	    </div>
</form>




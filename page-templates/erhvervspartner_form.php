<?php 

/*
		$company_name = sanitize_text_field($_POST['company_name']);
        $company_email = sanitize_email($_POST['company_email']);
        $faktura_email = sanitize_email($_POST['faktura_email']);
        $cvr = $_POST['cvr'];

        $contact_person = sanitize_text_field($_POST['contact_person']);
        $postalcode = sanitize_text_field($_POST['postalcode']);
        $city = sanitize_text_field($_POST['city']);
        $adresse = sanitize_text_field($_POST['adresse']);
        $phonenumber = sanitize_text_field($_POST['phonenumber']);





*/ ?>

<form  id="primaryPostForm" class="aid_form form wp-block-column" action="#" method="POST" enctype="multipart/form-data" data-url="<?php echo admin_url('admin-ajax.php'); ?>" novalidate>

<?php

$form_elem = array(

    //company name
    array('icon' =>'<i class="fa fa-user"></i>', 'type' => 'text', 'label' => 'Virksomhedens navn' , 'name' => 'company_name', 'placeholder' => 'Virksomhed' , 'required' => 'required', 'data_type' => 'not_empty', 'error_empty' => 'Din virksomhed er påkrævet', 'error_wrong' => '', 'script' =>''),
    //cvr nummer
    array('icon' =>'<i class="fa fa-calculator"></i>', 'type' => 'text', 'label' => 'CVR nummer' , 'name' => 'cvr', 'placeholder' => 'CVR nummer' , 'required' => 'required', 'data_type' => 'cvr', 'error_empty' => 'Virksomhedens cvr nummer er påkrævet', 'error_wrong' => 'Forkert format på cvr nummer', 'script' =>''),
    //cvr nummer
    array('icon' =>'<i class="fa fa-address-card"></i>', 'type' => 'text', 'label' => 'Adf' , 'name' => 'company_dep', 'placeholder' => 'Evt. Afdeling'  ),
 

    //company adress
    array('icon' =>'<i class="fa fa-address-card"></i>', 'type' => 'text', 'label' => 'Adresse' , 'name' => 'adresse', 'placeholder' => 'Adresse' , 'required' => 'required', 'data_type' => 'not_empty', 'error_empty' => 'Adresse er påkrævet', 'error_wrong' => '', 'script' =>''),
    //Postnummer
    array('icon' =>'<i class="fa fa-map-pin"></i>', 'type' => 'text', 'label' => 'Postnummer' , 'name' => 'postalcode', 'placeholder' => 'Postnummer' , 'required' => 'required', 'data_type' => 'postnummer', 'error_empty' => 'Postnummer er påkrævet', 'error_wrong' => 'Forkert formateret postnummer', 'script' => "onchange='ziplistener(this.value, `city` )'"),
    //city
    array('icon' =>'<i class="fa fa-building"></i>', 'type' => 'text', 'label' => 'By' , 'name' => 'city', 'placeholder' => 'By' , 'required' => 'required', 'data_type' => 'not_empty', 'error_empty' => 'Jeres by er påkrævet', 'error_wrong' => '', 'script' =>''),
    //phonenumber
    array('icon' =>'<i class="fa fa-phone-square"></i>', 'type' => 'text', 'label' => 'Telefonnummer' , 'name' => 'phonenumber', 'placeholder' => 'Telefonnummer' , 'required' => 'required', 'data_type' => 'phonenumber', 'error_empty' => 'Telefonnummer er påkrævet', 'error_wrong' => '', 'script' =>''),
    //company_email
    array('icon' =>'<i class="fa fa-envelope-square"></i>', 'type' => 'text', 'label' => 'E-mail' , 'name' => 'company_email', 'placeholder' => 'E-mail' , 'required' => 'required', 'data_type' => 'email', 'error_empty' => 'Virksomhedens email er påkrævet', 'error_wrong' => 'Forkert format på E-mail', 'script' =>''),
    //faktura email
    array('icon' =>'<i class="fa fa-envelope-square"></i>', 'type' => 'text', 'label' => 'E-mail fakturering' , 'name' => 'faktura_email', 'placeholder' => 'E-mail Fakturering' , 'required' => 'required', 'data_type' => 'email', 'error_empty' => 'Fakturerings E-mail er påkrævet', 'error_wrong' => 'forkert format på E-mail', 'script' =>''),
  //contact person
    array('icon' =>'<i class="fa fa-id-card"></i>', 'type' => 'text', 'label' => 'Kontaktperson' , 'name' => 'contact_person', 'placeholder' => 'Kontakt person' , 'required' => 'required', 'data_type' => 'not_empty', 'error_empty' => 'Kontakt person er påkrævet', 'error_wrong' => '', 'script' =>''),

    //E-mail kontaktperson
    array('icon' =>'<i class="fa fa-envelope-square"></i>', 'type' => 'text', 'label' => 'E-mail kontaktperson' , 'name' => 'contact_email', 'placeholder' => 'E-mail Kontaktperson' , 'required' => 'required', 'data_type' => 'email', 'error_empty' => 'Kontakt person E-mail er påkrævet', 'error_wrong' => 'forkert format på E-mail', 'script' =>''),

    //E-mail nyhedsbrav
    array('icon' =>'<i class="fa fa-envelope-square"></i>', 'type' => 'text', 'label' => 'E-mail nyhedsbrev' , 'name' => 'newsletter_email', 'placeholder' => 'E-mail nyhedsbrev' , 'required' => '', 'data_type' => 'email', 'error_empty' => 'Nyhedsbrev E-mail er påkrævet', 'error_wrong' => 'forkert format på E-mail', 'script' =>''),

    //E-mail indbydelser
    array('icon' =>'<i class="fa fa-envelope-square"></i>', 'type' => 'text', 'label' => 'E-mail indbydelser' , 'name' => 'invite_email', 'placeholder' => 'E-mail indbydelser' , 'required' => 'required', 'data_type' => 'email', 'error_empty' => 'E-mail for indbydelser er påkrævet', 'error_wrong' => 'forkert format på E-mail', 'script' =>''),



    //website
    array('icon' =>'<i class="fa fa-link"></i>', 'type' => 'text', 'label' => 'Website' , 'name' => 'website', 'placeholder' => 'Virksomhedens hjemmeside' , 'required' => 'required', 'data_type' => 'url', 'error_empty' => 'Virksomhedens hjemmeside', 'error_wrong' => 'Er ikke en korrekt url', 'script' =>''),


);

?>


<?php 

use Inc\Tools\Formbuilder;

$builder = new Formbuilder();

$builder->form_builder($form_elem);

?>






<?php $this->image_modal('Beskær logo', 'upload logo');?>
<div class="field-container">
            
            <input type="file" id="thumbnail" name="thumbnail"   style="display:none;" accept=".gif, .jpg,.jpeg, .png, .svg">
                        
                        
            <div id="uploaded_image"></div>
            <input type="hidden" name="file_dir" id="file_dir" data-type="extention" class="field-input" required="required"  >
           

</div>

        <?php wp_nonce_field( 'post_nonce', 'post_nonce_field' ); ?>

        <div class="field-container">
        <label for="gdpr"><input type="checkbox" class="field-input"  data-type="checkbox" id="gdpr"  name="gdpr"  required="required"> Gestus Nord må gemme ovenstående data.</label>

        <small class="field-msg_wrong error" data-error="wrongformatGdpr">Vi mangler jeres accept</small>


        </div>
      

        <input type="hidden" name="aid_type" value="<?php echo get_the_title() ?>">
        <input type="hidden" name="action" value="submit_erhvervspartner">
        
        
       
        
        <div class="field-container">
		<div class="btn_container">
            <button type="submit" id="submit" class="btn btn--main_red fontcolor--white " ><span>Bliv erhvervspartner</span></button>
            <label for="thumbnail" id="thumb_label" class="  btn btn--main_red fontcolor--white " style="visibility:hidden;"><span> Upload logo</span></label>
            <small class="field-msg error" data-error="invalidFile_dir" style="text-align: right;">Valg af logo er påkrævet</small>
            <small class="field-msg_wrong error" data-error="wrongformatFile_dir" style="text-align: right;">Forkert format a fil</small>
     
        </div>
		<small class="field-msg js-form-submission">Er ved at sende, et øjeblik&hellip;</small>
		<small class="field-msg success js-form-success">Din virksomhed er nu registreret!</small>
		<small class="field-msg error js-form-error">Der var et problem med at sende formen prøv igen!</small>
    </div>
    
</form>

<script>

<?php $output = get_option('gestus_page_manager');   ?>


let imagewidth =  <?php echo $output['partner_logo_width']?>;
let imageheight =   <?php echo $output['partner_logo_height']?>;

$(document).ready(function(){



  const selectElement = document.querySelector('#cvr');

  
 

  selectElement.addEventListener('change', (event) => {

    let cvr = /^([0-9]){8}$/;


    if(cvr.test(String(event.target.value.replace(/ /g,'')).toLowerCase())){

      document.getElementById("thumb_label").style.visibility = 'visible';

    }else {
      document.getElementById("thumb_label").style.visibility = 'hidden';

    }

});

$image_crop = $('#image_demo').croppie({
enableExif: true,
viewport: {
  width:imagewidth,
  height:imageheight,
  type:'square' //circle
},
boundary:{
  width:imagewidth,
  height:imageheight
},
enableOrientation: true
});

});
	
</script>
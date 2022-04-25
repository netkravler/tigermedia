<form  action="#" method="post" class="form" data-url="<?php echo admin_url('admin-ajax.php'); ?>" novalidate>


<?php

$form_elem = array(

    //full name
    array('icon' =>'<i class="fa fa-user"></i>','type' => 'text', 'label' => 'Fulde navn' ,  'name' => 'name', 'placeholder' => 'Fulde navn' , 'required' => 'required', 'data_type' => 'not_empty', 'error_empty' => 'Din fulde navn er påkrævet', 'error_wrong' => '', 'script' =>''),
    //company_email
    array('icon' =>'<i class="fa fa-envelope-square"></i>','type' => 'text', 'label' => 'Din E-mail' , 'name' => 'email', 'placeholder' => 'Din E-mail' , 'required' => 'required', 'data_type' => 'email', 'error_empty' => 'Din email er påkrævet', 'error_wrong' => 'Forkert format på E-mail', 'script' =>''),

);

?>


<?php 

use Inc\Tools\Formbuilder;

$builder = new Formbuilder();

$builder->form_builder($form_elem);

?>


	<div class="field-container">
		<textarea name="message" id="message" data-type="message" class="field-input" placeholder="Din besked" required="required" rows="5"></textarea>
		<small class="field-msg error" data-error="invalidMessage">En besked er påkrævet</small>
		<small class="field-msg_wrong error" data-error="wrongformatMessage">Din besked er ikke fyldestgørende nok</small>
	
	</div>

	<div class="field-container">

	<div class="comments-rating">
		
			<span class="rating-container" >
			
			<input type="radio" id="rating-5"  name="rating" value="5" data-type="checkbox" class="field-input" required="required"><label for="rating-5" ></label>
			<input type="radio" id="rating-4"  name="rating" value="4" data-type="checkbox" class="field-input"><label for="rating-4"></label>
			<input type="radio" id="rating-3"  name="rating" value="3" data-type="checkbox" class="field-input"><label for="rating-3"></label>
			<input type="radio" id="rating-2"  name="rating" value="2" data-type="checkbox" class="field-input"><label for="rating-2"></label>
			<input type="radio" id="rating-1"  name="rating" value="1" data-type="checkbox" class="field-input"><label for="rating-1" ></label>
			<!--<input type="radio" id="rating-0" class="star-cb-clear" name="rating" value="0"><label for="rating-0"></label>-->
			</span>
	</div>
			<small class="field-msg_wrong error" data-error="wrongformatRating">Din karakter betyder meget for os.</small>


</div>
	
	<div class="field-container">
		<label for="gdpr"><input type="checkbox" class="field-input"  data-type="checkbox" id="gdpr"  name="gdpr"  required="required"> Jeg acceptere at i gemmer mine data. </label>
		
		
		<small class="field-msg_wrong error" data-error="wrongformatGdpr">Det er påkrævet at vi beder om din accept</small>

		
	</div>

	

	<div class="field-container">
		<div>
            <button type="submit" class="btn btn--main_red fontcolor--white " >Send din besked</button>
        </div>
		<small class="field-msg js-form-submission">Er ved at sende, et øjeblik&hellip;</small>
		<small class="field-msg success js-form-success">Beskeden er sendt tak! <br>Dit indlæg kan ses når det er godkendt</small>
		<small class="field-msg error js-form-error">Der var et problem med at sende formen prøv igen!</small>
	</div>

	<input type="hidden" name="action" value="submit_testamonial">
	<input type="hidden" name="user_ip" value="<?php echo $_SERVER['REMOTE_ADDR']?>">

</form>
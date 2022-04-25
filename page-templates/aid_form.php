<?php

use Inc\Base\BaseController;



global $post;



$data = get_post_meta( $post->ID, '_gestus_aid_key', true );

if($data['activate_aid_type'] != 0){



?>


<div class="aid">

    <form id="primaryPostForm" class="aid_form form wp-block-column" action="#" method="POST"
        enctype="multipart/form-data" data-url="<?php echo admin_url('admin-ajax.php'); ?>" novalidate>
        <!--containing array of contact numbers-->
        <input type="hidden" id="cont_arr" name="cont_arr">

        <!--containing array of children numbers-->
        <input type="hidden" id="kids_arr" name="kids_arr">




        <?php if(isset( $data['post_extra_contact']) && $data['post_extra_contact'] == 1) { ?>
        <!-- container for contacts -->

        <fieldset class="contact_wrapper">
            <legend>Hvis du udfylder denne ansøgning på vegne af en anden </legend>


            <div id="contacts">

            </div>
            <input type="button" value="Så tryk her for at angive dine kontakt info" id="add_contact_button"
                class="btn btn--main_red fontcolor--white " onclick="addcontact();">
        </fieldset>


        <!-- end -->
        <?php }?>



        <fieldset>
            <legend>Civilstatus:</legend>
            <div class="field-container">
                <input type="hidden" id="post_ID" name="post_ID" value="<?php echo get_the_ID()?>">
                <!-- radio button to choose maritial status for applicants -->
                <label for="single">
                    <input type="radio" name="maritial_status" id="single" value="single" data-type="checkbox"
                        class="field-input" onclick="is_single(1)" required="required"> Enlig</label>

                <label for="gift">
                    <input type="radio" name="maritial_status" id="gift" value="gift" data-type="checkbox"
                        class="field-input" onclick="is_hooked('gift_med')"> Gift</label>

                <label for="samlever">
                    <input type="radio" name="maritial_status" id="samlever" value="Samlever" data-type="checkbox"
                        class="field-input" onclick="is_hooked('Samlever')"> Samlever</label>

                <small class="field-msg_wrong error" data-error="wrongformatMaritial_status">Valg af civilstatus
                    påkrævet</small>

            </div>
        </fieldset>
        <!--  end -->





        <!-- Container for filling in applicants info. feed comming from javascript -->

        <div id="applicants" class="cards">

            <fieldset id="card1" class="cards__card"></fieldset>

            <fieldset id="card2" class="cards__card"></fieldset>

            <fieldset id="card3" class="cards__card"><?php if(isset( $data['aid_info']) &&  $data['aid_info']) { ?>
                <legend>info</legend><?php } ?> <article class="aid_info_type " id="aid_info_type">
                    <?php if(isset( $data['aid_info']) &&  $data['aid_info']) { ?><?php echo  $data['aid_info']?>
                    <?php }?></article>
            </fieldset>
        </div>

        <!-- end -->



        <?php $kidslabel = isset( $data['activate_konfirmation']) && $data['activate_konfirmation'] == 1 ? 'Konfirmandens alder og køn' : 'Info om børn under 18 år:';?>                      


        <?php if(isset( $data['post_extra_kids']) && $data['post_extra_kids'] == 1) { ?>
        <!-- Dropdown to choose number of children -->
        <fieldset>
            <legend><?php echo $kidslabel ?></legend>

            <div id="childrens" class="children"></div>
         
            <input type="button" value="Tilføj et barn" id="add_child_button"
                class="btn btn--main_red fontcolor--white " onclick="add_children();">
               

        </fieldset>

        <!-- end -->




        <?php }?>


        <?php if(isset( $data['activate_konfirmation']) && $data['activate_konfirmation'] == 1) { ?>
        <div class="field-container">
       
        <label for="storcenter">
        
        <fieldset>
        <br>
        <h3>Konfirmationshjælpen i 2021 består af et gavekort på 1.800 kr.</h3>
        <h4>Vælg afhentningssted</h4><br>
<style>


.radio_flex{

    display:flex;
    align-items: center;
}

.radio_flex > input{

    margin-right: 10px;
}

</style>


        <?php foreach($this->pickuppoints as $points => $value){
            
            //echo $value['navn'];
            ?>

<?php //echo $value['navn']?>
<?php //echo $value['adresse']?>

            <p class="radio_flex"><input type="radio" id="<?php echo $value['navn']?>" name="pickup" value="<?php echo $value['adresse']?>" class="field-input" data-type="checkbox">
            <label for="<?php echo $value['navn']?>"><?php echo $value['adresse']?>
            </p>
            
       <?php }?>





        <small class="field-msg_wrong error" data-error="wrongformatPickup">Vælg afhentningssted </small>
        </fieldset>
        </div>



            <?php }?>


            <?php if(isset( $data['kids_total']) && $data['kids_total'] == 1) { ?>


                <div class="field-container">
                    <label for="kids_total">Angiv antal af børn i husholdet</label>
                    <div class="icon_container"><input style="width:auto; padding: 8px;" type="text" name="kids_total" id="kids_total"  required="required" data-type="number" class="field-input" ></div>
                    <small class="field-msg error" data-error="invalidKids_total">Antal børn er påkrævet</small></div>
                    <small class="field-msg_wrong error" data-error="wrongformatKids_total">Antal børn er påkrævet </small>
            <?php }?>





        <?php if(isset( $data['aid_comment']) && $data['aid_comment'] == 1) { ?>

        <fieldset>
            <legend>Hvad er årsagen til at du ansøger om hjælp?</legend>

            <div id="comment"></div>

        </fieldset>


        <script>
            document.addEventListener("DOMContentLoaded", function () {

                <?php
                if (isset($data['start_width_one_kid']) && $data['start_width_one_kid'] == 1) {
                    ?>

                    add_children();

                    <?php
                } ?>
            });
        </script>

        <?php }?>
        <input type="hidden" name="submitted" id="submitted" value="true" />





        <?php wp_nonce_field( 'post_nonce', 'post_nonce_field' ); ?>

        <div class="field-container">
            <label for="gdpr"><input type="checkbox" class="field-input" data-type="checkbox" id="gdpr" name="gdpr"
                    required="required"> Gestus Nord må gemme og bruge ovenstående data til viderebehandling af
                ansøgningen </label>

            <small class="field-msg_wrong error" data-error="wrongformatGdpr">Det er påkrævet at vi beder om din
                accept</small>


        </div>

        <div class="field-container">
            <label for="moreactivities"><input type="checkbox" class="field-input"  id="moreactivities" name="moreactivities"
                    > Gestus Nord må gerne kontakte mig/os angående andre aktiviteter fx Gestus Kids/Teen/Camp/Fødselsdag </label>




        </div>

        <input type="hidden" name="aid_id" value="<?php echo get_the_ID() ?>">
        <input type="hidden" name="aid_type" value="<?php echo get_the_title() ?>">
        <input type="hidden" name="action" value="submit_gestus_applicants">
        <input type="hidden" name="user_ip" value="<?php echo $_SERVER['REMOTE_ADDR']?>">

        <!--<button type="submit" class="button submit btn btn--main_red fontcolor--white" id="submit" name="submit"   style="display:block">Send ansøgning</button>-->


        <div class="field-container">
            <div>
                <button type="submit" class="submit btn btn--main_red fontcolor--white ">Send din ansøgning</button>
            </div>
            <small class="field-msg js-form-submission">Er ved at sende, et øjeblik&hellip;</small>
            <small class="field-msg success js-form-success">Din ansøgning er sendt tak!</small>
            <small class="field-msg error js-form-error">Der var et problem med at sende formen prøv igen!</small>
        </div>


    </form><?php }
    
    else{

         echo '<h2>'. get_the_title() .' er ikke aktiv endnu</h2>';
    }
    
    ?>
</div>
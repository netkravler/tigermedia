<div class="tab-pane" id="tab-4">

<form method="POST" action="options.php" >
<!--<input type="hidden" name="edit_field" value="post">-->
<input type="hidden" name="manager" value="use_mails_on_posttype">

<?php 

    settings_fields( 'choose_mail_settings' );
    do_settings_sections( 'use_mails_on_posttype' );
    submit_button('Gem ændringer', 'primary ', 'submit_use_mails', false);

?>

</form>
</div>
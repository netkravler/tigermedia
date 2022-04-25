<div class="tab-pane" id="tab-3">

<form method="POST" action="options.php">

<input type="hidden" name="manager" value="gestus_page_manager">
<?php 



settings_fields( 'gestus_page_settings' );
do_settings_sections( 'gestus_page_manager' );
submit_button();





?>
</form>
</div>
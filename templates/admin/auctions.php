<div class="tab-pane" id="tab-6">


<h2>Auktions indstillinger</h2>

<form method="POST" action="options.php">

<input type="hidden" name="manager" value="gestus_auction_manager">
<?php 



settings_fields( 'gestus_auction_settings' );
do_settings_sections( 'gestus_auction_manager' );
submit_button();





?>
</form>
</div>

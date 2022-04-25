
        <div class="tab-pane <?php echo !$_POST || $_POST['manager'] ? 'active': '';?>" id="tab-1">

<form method="POST" action="options.php">


<input type="hidden" name="edit_manager">
<?php 

    settings_fields( 'gestus_settings_manager' );
    do_settings_sections( 'gestus_nord_admin' );
    submit_button();

?>

</form>

</div>
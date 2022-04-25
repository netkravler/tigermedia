      <!-- table of settings for activity-->

      <div class="tab-pane <?php echo isset($_POST['edit_activity']) ? 'active': '';?>" id="tab-2">


      <?php

if(get_option('gestus_settings_manager')){

    $tbl = '<div class="table-container" role="table" aria-label="Destinations">' .
    '<div class="flex-table header" role="rowgroup">' .
    '<div class="flex-row first" role="columnheader">Aktivitet</div>' .
    '<div class="flex-row first" role="columnheader">Aktiviteten omhandler</div>' .
    '<div class="flex-row first" role="columnheader">Er aktiv</div>' .
    '<div class="flex-row" role="columnheader">Børne attest påkrævet</div>' .
    '<div class="flex-row" role="columnheader">Actions</div>' .
    

    '</div>';
    echo $tbl;



    $options = get_option('gestus_settings_manager');




foreach($options as $option){


?>

<div class="flex-table row" role="rowgroup">

<?php 

$link = isset($option['activity']) ? $option['page_id'] :'';
$get_page = '<a href="'. get_the_permalink( $link ) .'" target="_blank">'. $option['activity'] .'</a>';

?>
    <div class="flex-row" role="cell"><?php echo $get_page; ?>
    </div>

    <div class="flex-row" role="cell">
        <?php echo isset($option['description']) ? $this->cut_string($option['description'], 30) :''; ?>
    </div>
    <div class="flex-row" role="cell"><?php echo isset($option['is_active']) ? $option['is_active'] : 'false' ; ?>
    </div>
    <div class="flex-row" role="cell"><?php echo isset($option['attest']) ? $option['attest'] : 'false' ; ?>
    </div>

    <div class="flex-row" role="cell">
        <form method="POST" action="" class="inline-block">

            <input type="hidden" name="edit_activity">
            <input type="hidden" name="edit_field" value="<?php echo $option['activity'] ?>">

            <?php submit_button('edit', 'primary small', 'submit', false)?>
        </form>

        <form method="POST" action="options.php" class="inline-block">

            <?php settings_fields( 'gestus_manager_settings' );?>
             <input type="hidden" name="page_id" value="<?php echo $option['page_id'] ?>">
            <input type="hidden" name="remove" value="<?php echo $option['activity'] ?>">
            <?php submit_button('Delete', 'delete small', 'submit', false, array('onclick' => 'return confirm("Er du sikker på du vil slette '.$option['activity']. ', Siden i Gestus Aktiviteter omhandelende denne slettes også ")'))?>
        </form>
    </div>
</div>
<?php
}
?>








<!--table of activity ends here-->
<form method="POST" action="options.php">

<input type="hidden" name="edit_post" value="<?php echo isset($_POST['edit_field']) ? $_POST['edit_field'] : '' ; ?>">
<input type="hidden" name="page_id" value="<?php echo $option['page_id'] ?>">
<?php 

settings_fields( 'gestus_manager_settings' );
do_settings_sections( 'gestus_settings_manager' );
submit_button();

?>

</form>
</div>
</div>
<?php
}


?>
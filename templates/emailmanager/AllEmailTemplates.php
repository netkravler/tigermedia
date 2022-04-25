<div class="tab-content ">

    <div class="tab-pane <?php echo !isset($_POST['edit_field']) ? 'active': '';?>" id="tab-1">

        <p>Alle E-mail skabeloner</p>
        <?php

if(get_option('gestus_email_manager')){

    $tbl = '<div class="table-container" role="table" aria-label="Destinations">' .
    '<div class="flex-table header" role="rowgroup">' .
    '<div class="flex-row first" role="columnheader">Skabelon for - mail type - post_type</div>' .
    '<div class="flex-row first" role="columnheader">Respons sendes til brugere </div>' .

    '<div class="flex-row" role="columnheader">Actions</div>' .
    

    '</div>';
    echo $tbl;


$options = get_option('gestus_email_manager');





foreach($options as $option){


?>

        <div class="flex-table row" role="rowgroup">

            <div class="flex-row" role="cell">
                <?php echo isset($option['emails']) ?  $option['emails'] .' - '. $option['mail_type'] .' - '. $option['post_types']: ''; ?>
            </div>
            <div class="flex-row" role="cell">
                <?php echo isset($option['gestus_reciever']) ? $option['gestus_reciever'] : '' ; ?> </div>
            <div class="flex-row" role="cell">
                <form method="POST" action="" class="inline-block" autocomplete="off">
                    <input type="hidden" name="manager" value="gestus_email_manager">
                    <input type="hidden" name="edit_field" value="<?php echo $option['emails'] ?>">

                    <?php submit_button('edit', 'primary small', 'submit_emails', false)?>
                </form>

                <form method="POST" action="options.php" class="inline-block">
                    <?php settings_fields( 'gestus_email_settings' );?>
                    <input type="hidden" name="manager" value="gestus_email_manager">
                    <input type="hidden" name="remove" value="<?php echo $option['emails'] ?>">
                    <?php submit_button('Delete', 'delete small', 'submit_settings', false, array('onclick' => 'return confirm("Er du sikker på du vil slette '.$option['emails']. ', dine data vil være bevarede i DB ")'))?>
                </form>
            </div>
        </div>
        <?php
}
?>
    </div>
    <?php
}


    ?>




</div>
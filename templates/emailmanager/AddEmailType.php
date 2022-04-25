<?php

use Inc\Base\ResponseMailController;


    
$get_type = new ResponseMailController();


?>
<div class="tab-pane <?php echo isset($_POST['manager']) && $_POST['manager'] == 'set_email_type' ? 'active': '';?>" id="tab-2">


<?php





if(get_option('set_email_type')){

    $tbl = '<div class="table-container" role="table" aria-label="Destinations">' .
    '<div class="flex-table header" role="rowgroup">' .
    '<div class="flex-row first" role="columnheader">Email af type</div>' .
    '<div class="flex-row first" role="columnheader">Vælg post type</div>' .
    '<div class="flex-row first" role="columnheader">Beskrivelse</div>' .


    '<div class="flex-row" role="columnheader">Actions</div>' .
    

    '</div>';
    echo $tbl;


$options = get_option('set_email_type');


foreach($options as $option){

 $emailtype = isset($option['email_type']) ?  $option['email_type']  : '';
?>

   

    <div class="flex-table row" role="rowgroup">
   
    <div class="flex-row" role="cell"><?php echo  $emailtype ?> </div>
    <div class="flex-row" role="cell">
    
    <form method="POST" action="#" autocomplete="off" style="padding: 5px;display:grid; width: 100%;  grid-template-columns: 70% 20%; gap: 10px; align-items:center;">
    <?php $get_type->Find_Mail_Type('gestus_email_manager', get_option('use_mails_on_posttype') , $option['email_type'] , $options);?></div>

    </form>
    <div class="flex-row" role="cell"><?php echo isset($option['email_description']) ?  $option['email_description'] : ''; ?> </div>

     <div class="flex-row" role="cell">
    <form method="POST" action="" class="inline-block">
    <input type="hidden" name="manager" value="set_email_type">
    <input type="hidden" name="edit_field" value="<?php echo $option['email_type'] ?>">

    <?php submit_button('edit', 'primary small', 'submit_set_email_type', false)?>
    </form>

    <form method="POST" action="options.php" class="inline-block">
    <?php settings_fields( 'choose_mail_type' );?>
    <input type="hidden" name="edit_field" value="<?php echo $option['email_type'] ?>">
    
    <input type="hidden" name="manager" value="set_email_type">
    <input type="hidden" name="remove" value="<?php echo $option['email_type'] ?>">
    <?php submit_button('Delete', 'delete small', 'submit_email_type', false, array('onclick' => 'return confirm("Er du sikker på du vil slette '.$option['email_type']. ', dine data vil være bevarede i DB ")'))?>
    </form>
    </div></div>
 <?php
}
?>
</div>
<?php
}


    ?>

<form method="POST" action="options.php" >
<input type="hidden" name="manager" value="set_email_type">
    <?php 

        settings_fields( 'choose_mail_type' );
        do_settings_sections( 'set_email_type' );
        submit_button('Gem ændringer', 'primary', 'submit_set_email', false);

    ?>

</form>

</div>
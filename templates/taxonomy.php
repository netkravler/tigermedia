

<article class="wrap">
<h1>Taxonomy manager</h1>

<ul class="nav nav-tabs">
        <li class="<?php echo !isset($_POST['edit_field']) ? 'active': '';?>"><a href="#tab-1">Alle Taxonomies</a></li>
        <li class="<?php echo isset($_POST['edit_field']) ? 'active': '';?>"><a href="#tab-2" ><?php echo !isset($_POST['edit_field']) ? 'Tilføj Taxonomy': 'Rediger taxonomy ';?> </a></li>
        <li ><a href="#tab-3" >Opdatering</a></li>
       
    </ul>

    <div class="tab-content ">

        <div class="tab-pane <?php echo !isset($_POST['edit_field']) ? 'active': '';?>" id="tab-1">

    <p>Alle Takonomies</p>
    <?php

if(get_option('gestus_tax_manager')){

    $tbl = '<div class="table-container" role="table" aria-label="Destinations">' .
    '<div class="flex-table header" role="rowgroup">' .
    '<div class="flex-row first" role="columnheader">Taxonomy</div>' .
    '<div class="flex-row" role="columnheader">Singular name</div>' .
    '<div class="flex-row" role="columnheader">Hierarchical</div>' .
    '<div class="flex-row" role="columnheader">Actions</div>' .
    

    '</div>';
    echo $tbl;
$options = get_option('gestus_tax_manager');


foreach($options as $option){


?>

    <div class="flex-table row" role="rowgroup">
   
    <div class="flex-row" role="cell"><?php echo $option['taxonomy'] ?> </div>
    <div class="flex-row" role="cell"><?php echo $option['singular_name'] ?></div>
    <div class="flex-row" role="cell"><?php echo mytranslate( isset($option['hierarchical'])) ?: false ?></div>

    <div class="flex-row" role="cell">
    <form method="POST" action="" class="inline-block">
    <input type="hidden" name="manager" value="gestus_tax_manager">
    <input type="hidden" name="edit_field" value="<?php echo $option['taxonomy'] ?>">

    <?php submit_button('edit', 'primary small', 'submit', false)?>
    </form>

    <form method="POST" action="options.php" class="inline-block">
    <input type="hidden" name="manager" value="gestus_tax_manager">
    <?php settings_fields( 'gestus_tax_settings_manager' );?>
    <input type="hidden" name="remove" value="<?php echo $option['taxonomy'] ?>">
    <?php submit_button('Delete', 'delete small', 'submit', false, array('onclick' => 'return confirm("Er du sikker på du vil slette '.$option['taxonomy']. ', dine data vil være bevarede i DB ")'))?>
    </form>
    </div></div>
 <?php
}
?>
</div>
<?php
}


    ?>




    </div>


<div class="tab-pane <?php echo isset($_POST['edit_field']) ? 'active': '';?>" id="tab-2">

    <form method="POST" action="options.php" >

    <input type="hidden" name="manager" value="gestus_tax_manager">
        <?php 

            settings_fields( 'gestus_tax_settings_manager' );
            do_settings_sections( 'gestus_tax_manager' );
            submit_button();

        ?>

    </form>

</div>

<div class="tab-pane" id="tab-3">

    Post type editor
</div>



</div>

</article>

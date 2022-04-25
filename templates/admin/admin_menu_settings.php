<!-- table of settings for activity-->

<div class="tab-pane <?php echo isset($_POST['edit_menu']) ? 'active': '';?>" id="tab-4">


    <?php

$admin_role_set = get_role( 'editor' )->capabilities;

//var_dump($admin_role_set);


if(get_option('page_menu_manager')){

    $tbl = '<div class="table-container" role="table" aria-label="Destinations">' .
    '<div class="flex-table header" role="rowgroup">' .
    '<div class="flex-row first" role="columnheader">Side titel</div>' .
    '<div class="flex-row first" role="columnheader">Menu titel</div>' .    
    '<div class="flex-row first" role="columnheader">Menu slug</div>' .
    '<div class="flex-row first" role="columnheader">kan ses af</div>' .
    '<div class="flex-row first" role="columnheader">menu ikon</div>' .

    '<div class="flex-row" role="columnheader">Actions</div>' .
    

    '</div>';
    echo $tbl;



    $menuoptions = get_option('page_menu_manager');




foreach($menuoptions as $menuoption){


?>

    <div class="flex-table row" role="rowgroup">


        <div class="flex-row" role="cell">
            <?php echo isset($menuoption['admin_menu']) ? $this->cut_string($menuoption['admin_menu'], 30) :''; ?>
        </div>

        <div class="flex-row" role="cell">
            <?php echo isset($menuoption['menu_title']) ? $this->cut_string($menuoption['menu_title'], 30) :''; ?>
        </div>

        <div class="flex-row" role="cell">
            <?php echo isset($menuoption['menu_slug']) ? $this->cut_string($menuoption['menu_slug'], 30) :''; ?>
        </div>

        <div class="flex-row" role="cell">
            <?php echo isset($menuoption['capability']) ? $this->cut_string($menuoption['capability'], 30) :''; ?>
        </div>

        <div class="flex-row" role="cell">
            <?php echo isset($menuoption['admin_menu']) ? $this->cut_string($menuoption['admin_menu'], 30) :''; ?>
        </div>


        <div class="flex-row" role="cell">
            <form method="POST" action="" class="inline-block">
                <input type="hidden" name="edit_menu">
                <input type="hidden" name="edit_field" value="<?php echo $menuoption['admin_menu'] ?>">

                <?php submit_button('edit', 'primary small', 'submit', false)?>
            </form>

            <form method="POST" action="options.php" class="inline-block">
                <input type="hidden" name="edit_menu">
                <?php settings_fields( 'gestus_menu_settings' );?>
                <input type="hidden" name="manager" value="page_menu_manager">
                <input type="hidden" name="remove" value="<?php echo $menuoption['admin_menu'] ?>">
                <?php submit_button('Delete', 'delete small', 'submit', false, array('onclick' => 'return confirm("Er du sikker på du vil slette '.$menuoption['admin_menu']. ' ")'))?>
            </form>
        </div>
    </div>
    <?php
}
?>

    <?php
}


?>


    <!--table of activity ends here-->
    <form method="POST" action="options.php">

            <input type="hidden" name="edit_post"  value="<?php echo isset($_POST['edit_field']) ? $_POST['edit_field'] : '' ; ?>">
            <input type="hidden" name="manager" value="page_menu_manager">
            <input type="hidden" name="edit_menu">
        <?php 

            settings_fields( 'gestus_menu_settings' );
            do_settings_sections( 'page_menu_manager' );
            submit_button();

        ?>

    </form>
</div>
    <?php if(get_option('page_menu_manager')){ ?>

</div>
<?php }?>



<article class="wrap">
<h1>Page Template manager</h1>

<ul class="nav nav-tabs">
        <li class="<?php echo !isset($_POST['edit_template']) ? 'active': '';?>"><a href="#tab-1">Alle Skabeloner</a></li>
        <li class="<?php echo isset($_POST['edit_template']) ? 'active': '';?>"><a href="#tab-2" ><?php echo !isset($_POST['edit_template']) ? 'Tilføj Skabelon': 'Rediger Skabelon ';?> </a></li>
        <li ><a href="#tab-3" >Opdatering af skabeloner</a></li>
        <li ><a href="#tab-4" >Skabelon template</a></li>
       
    </ul>

    <div class="tab-content ">

        <div class="tab-pane <?php echo !isset($_POST['edit_template']) ? 'active': '';?>" id="tab-1">

    <p>Alle Skabeloner</p>
    <?php

if(get_option('templates_manager')){

    $tbl = '<div class="table-container" role="table" aria-label="Destinations">' .
    '<div class="flex-table header" role="rowgroup">' .
    '<div class="flex-row first" role="columnheader">Skabelon navn</div>' .
    '<div class="flex-row" role="columnheader">Skabelon fil</div>' .
    '<div class="flex-row" role="columnheader">Er aktiveret</div>' .
    '<div class="flex-row" role="columnheader">Actions</div>' .
    

    '</div>';
    echo $tbl;
$options = get_option('templates_manager');


foreach($options as $option){


?>

    <div class="flex-table row" role="rowgroup">
   
    <div class="flex-row" role="cell"><?php echo $option['template'] ?> </div>
    <div class="flex-row" role="cell"><?php echo $option['file_name'] ?></div>
    <div class="flex-row" role="cell"><?php echo mytranslate( isset($option['is_active_template'])) ?: false ?></div>

    <div class="flex-row" role="cell">
    <form method="POST" action="" class="inline-block"  enctype=”multipart/form-data”>

    <input type="hidden" name="edit_template" value="<?php echo $option['template'] ?>">

    <?php submit_button('edit', 'primary small', 'submit', false)?>
    </form>

    <form method="POST" action="options.php" class="inline-block">
    <?php settings_fields( 'template_settings_manager' );?>
    <input type="hidden" name="remove" value="<?php echo $option['template'] ?>">
    <?php submit_button('Delete', 'delete small', 'submit', false, array('onclick' => 'return confirm("Er du sikker på du vil slette '.$option['template']. ', dine data vil være bevarede i DB ")'))?>
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


<div class="tab-pane <?php echo isset($_POST['edit_template']) ? 'active': '';?>" id="tab-2">

    <form method="POST" action="options.php" enctype=”multipart/form-data”>

        <?php 

            settings_fields( 'template_settings_manager' );
            do_settings_sections( 'templates_manager' );
            submit_button();

        ?>

    </form>

</div>

<div class="tab-pane" id="tab-3">

    Post type editor


</div>
<div class="tab-pane" id="tab-4">
<article>
<p>Opret dokument som uploades her på fanen tilføj Skabelon, dokumentet skal indeholde basiskoden der er herunder. Erstat "Skabelon Kode" med din ønskede kode </p>

    <p>Feltet med Template Name: skal indeholde samme værdi som input feltet "skabelon title" på fanen tilføj Skabelon</p>
<code>
<pre>
&lt;?php


/* 

Template Name: Samme title som skrives her som i title felte i formen

*/

get_header();


?&gt;

<h2>Skabelon Kode </h2>


&lt;?php

get_footer();

</pre>
</code>
</article>
</div>
</div>

</article>


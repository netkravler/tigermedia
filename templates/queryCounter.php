<?php 






    $aid_args = array(

        'post_type' => 'gestus_aid',
        'posts_per_page' => -1,
        'fields'=> 'ids',
        'post_status' => 'publish'
        );
        
    $aid_ids = get_posts($aid_args);

function get_applicants($arr){


    echo '<h2>' . $arr['adresse']. '</h2>';




$args = array(

    'post_type' => 'applicants',
    'posts_per_page' => -1,
    'fields'=> 'ids',
    'post_status' => 'publish'
);

$posts_ids = get_posts($args);

$tbl = '<button onclick="printDiv(`table-container'.$arr['navn'].'`, `'.$arr['adresse'].'`)">print</button>'.
'<section id="table-container'.$arr['navn'].'">'.
'<div class="table-container"  role="table" aria-label="Destinations" >' .
'<div class="flex-table header" role="rowgroup">' .
'<div class="flex-row first" role="columnheader">Fornavn</div>' .
'<div class="flex-row first" role="columnheader">Efternavn </div>' .
'<div class="flex-row first" role="columnheader">Telefonnummer </div>' .
'<div class="flex-row first" role="columnheader">Email </div>' .

'<div class="flex-row" role="columnheader">Konfirmand</div>' .
'<div class="flex-row" role="columnheader">Køn</div>' .
'<div class="flex-row" role="columnheader">Afhent gavekort</div>' .


'</div>';
echo $tbl;

foreach( $posts_ids as $id ) {


    //echo $id .'<br>';
   $data = get_post_meta($id , '_gestus_applicants_key', true);

   if(/*$data['approved'] == 1 &&*/ isset($data['pickup']) && $data['pickup'] == $arr['adresse'] /* && $data['post_id'] == $_POST['post_id']*/){

    ?>
<style>


</style>

<div class="flex-table row" role="rowgroup" style="hover{ background-color #ddd}; cursor:pointer" onclick="location.href=(`/wp-admin/post.php?post=<?php echo $id?>&action=edit`)">

<div class="flex-row " role="cell"><?php echo $data['parents'][0]['firstname']; ?></div>
<div class="flex-row " role="cell"><?php echo $data['parents'][0]['lastname']; ?></div>
<div class="flex-row " role="cell"><?php echo $data['parents'][0]['phonenumber']; ?></div>
<div class="flex-row " role="cell"><?php echo $data['parents'][0]['email']; ?></div>
<div class="flex-row " role="cell"><?php echo $data['kids'][0]['name']; ?></div>
<div class="flex-row " role="cell"><?php echo $data['kids'][0]['gender'] == 'boy' ? 'Dreng': 'Pige' ; ?></div>
<div class="flex-row " role="cell"><?php echo $data['pickup']; ?></div>

</div>

    <?php

    

   }

    

}

echo '</div></section><br><br>';
}
?>

<?php




foreach($aid_ids as $aid_id => $value){

   /* echo '<form method="post" style="display: inline-block; padding: 10px;">';
   echo '<button class="button button-primary" name="post_id" value="'.$value.'">'.get_the_title($value).'</button>';
    echo '</form>';*/

}




 foreach($this->pickuppoints as $points => $value){
            
    

    get_applicants($value);


?>

<?php //echo $value['navn']?>
<?php //echo $value['adresse']?>

<?php }

echo '<script src="'.$this->plugin_url.'/common/assets/print/js/print-min.js"></stript>';
<?php







$args = array(

    'post_type' => 'bestyrrelse',
    'post_status' => 'publish',
    'orderby'   => 'menu_order',
    'order' => 'ASC',
    'posts_per_page' => -1,

    'meta_query' => array(

        array(

            'key' => '_gestus_management_key',
            'value' => 's:8:"approved";i:1;s:8:"featured";i:1;',
            'compare' => 'LIKE'
        )
    )


);


$query = new WP_Query( $args);



if ( $query->have_posts()):

    echo '<ul class="management">';

while ($query->have_posts()) : $query->the_post();


$data = get_post_meta( get_the_ID(), '_gestus_management_key', true );


    $full_name = $data['firstname'] .' '. $data['lastname'].'<br>';

    echo '<li >'; 
    echo '<h6>'.$data['position'] . '</h6>';
    echo $full_name;      
   
    echo $data['company']; 

    echo '<p>' . get_the_content() .'</p>';
   // echo '<figure>' . get_the_post_thumbnail(get_the_ID(), 'thumbnail', array( 'class' => 'alignleft' , 'alt' => 'billede af '. $full_name)); 
    //echo '</figure></li>';

endwhile;
    



    echo '</ul>';




endif;

wp_reset_postdata();


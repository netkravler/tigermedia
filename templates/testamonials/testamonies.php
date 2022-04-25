<?php





$total_args = array(

    'post_type' => 'testamonial',
    'post_status' => 'publish',
    'posts_per_page' => -1,

    'meta_query' => array(

        array(

            'key' => '_gestus_testimonial_key',
            'value' => 's:8:"approved";i:1;s:8:"featured";i:1;',
            'compare' => 'LIKE'
        )
    )


);

//total sum og grades given by people
$tot_num_grades = 0;

$tot_query = new WP_Query($total_args);


$num = $tot_query->post_count; 

//echo $num;

if($tot_query->have_posts()):

    
    while($tot_query->have_posts()): $tot_query->the_post();

        $total_rate_data = get_post_meta( get_the_ID(), '_gestus_testimonial_key', true );
        $total_data_rating = isset($total_rate_data['rating']) ? $total_rate_data['rating'] : '';
        
        $tot_num_grades += intval($total_data_rating);



    endwhile;

echo 'gennemsnit for kommentarer';
echo display_average_rating( round( $tot_num_grades / $num , 1 ), $num );
 
 else : 
   
    ?>

        <p>Der er ingen udtalelser endnu, vær den første</p>
   
    <?php

endif;
wp_reset_postdata();



$args = array(

    'post_type' => 'testamonial',
    'post_status' => 'publish',
    'orderby' => 'rand',
    'order' => 'ASC',
    'posts_per_page' => 5,

    'meta_query' => array(

        array(

            'key' => '_gestus_testimonial_key',
            'value' => 's:8:"approved";i:1;s:8:"featured";i:1;',
            'compare' => 'LIKE'
        )
    )


);


$query = new WP_Query( $args);

$total_rating = 0;

if ( $query->have_posts()):

    echo '<ul style="margin: 0;">';

while ($query->have_posts()) : $query->the_post();


$data = get_post_meta( get_the_ID(), '_gestus_testimonial_key', true );
$user_rating = isset($data['rating']) ? $data['rating'] : '';

$total_rating += intval($user_rating);

    echo '<li style="list-style-type: none">'. get_the_title().'<p>'.get_the_content().'</p><footer>'. display_rating_stars( $user_rating ) .'</footer></li>';

endwhile;
    



    echo '</ul>';




endif;

wp_reset_postdata();

function display_rating_stars( $num_stars ) {



		$stars = '<p class="stars">';
		for ( $i = 1; $i <= $num_stars; $i ++ ) {
			$stars .= '<span class="dashicons dashicons-star-filled"></span>';
		}
        $stars        .= '</p>';
        
        return $stars;

    }
    
    function display_average_rating( $average , $num) {

            $icon_width = 20;
    
           

            $stars = '';
        for ( $i = 0; $i < $average; $i ++ ) {
    



            $width = intval( ($i+1) - $average > 0 ? ($icon_width - ( ( ($i+1) - $average ) * $icon_width )) : $icon_width );
            

    
            if ( 0 === $width ) {
                continue;
            }
    
            $stars .= '<span style="overflow:hidden; width:' . $width . 'px" class="dashicons dashicons-star-filled"></span>';
    

        }
    
    

        $custom_content = '<p class="average-rating">' .  $stars . ' '. $average.' baseret på '.$num.' udtalelser</p>';
    

    
        return $custom_content;
    }
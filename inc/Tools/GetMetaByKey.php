<?php

namespace Inc\Tools;
use WP_Query;


class GetMetaByKey
{

    

    public function register(){


    }

    public  function get_meta_value_by_key($posttype, $meta_key, $meta_value,  $limit = 1){
                // WP_Query arguments
            $args = array (
                'post_type'              => $posttype ,
                'post_status'            => array( 'publish' ),
                'posts_per_page'         => $limit,
                'meta_query'             => array(
                    array(
                        'key'       => $meta_key ,
                        'value'     =>  $meta_value,
                        'compare'   => 'LIKE'
                    ),
                ),
            );

            // The Query
            $query = new WP_Query( $args );

            // The Loop
            if ( $query->have_posts() ):
                while( $query->have_posts()) : $query->the_post(); 
               return get_the_ID();
                
            endwhile;

            else : 
              return false;
            endif;
            
            // Restore original Post Data
        wp_reset_postdata();
    }
}

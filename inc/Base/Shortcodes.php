<?php

namespace Inc\Base;

use Inc\Tools\Tool_box;

use WP_Query;

class Shortcodes extends BaseController
{
    public $tool_box;

    public $tools;

    public function register(){

        $this->tool_box = new Tool_box();


        add_shortcode ('siblings',array($this, 'rt_list_children'));
        add_shortcode( 'ul_list', array($this,'post_type_ul_list')); 
        add_shortcode( 'img_url_list', array($this, 'img_url_list')); 

        add_shortcode('posts_by_taxonomy', array($this, 'posts_by_taxonomy'));

        add_shortcode('year', array($this, 'year_shortcode'));

        add_shortcode('all_posts', array($this, 'count_posts'));


        

    }


    
//sidebar siblings menu
public function rt_list_children() {
    global $post;
    $page = $post->ID;
    if ( $post->post_parent ) {
        $page = $post->post_parent;
    }
    $children = wp_list_pages( array(
        'child_of' => $page,
        'title_li'    => '',
		'echo' => '0'
    ) );

    if ($children) {
       $output = '<ul>';
       $output .= $children;
       $output .= '</ul>';
    } else {
       $output = '';
    }
    return $output;
}


public function post_type_ul_list( $atts, $content ){


    $now = strtotime(current_time($this->timeformat));

    $value = shortcode_atts( array(
    
    'posttype' => 'bestyrrelse',
    'num_posts' => -1,
    'links' => '1',

    
    ), $atts );
    
    ob_start();
    
    global $post;
    
    $numpost = ! $value['num_posts'] ? -1 : $value['num_posts'] ;
    
    $posts = new \WP_Query( array(
    
    'post_type' => $value['posttype'],
    'posts_per_page' => $numpost,
    'orderby'=>'menu_order', 
    'order' => 'ASC',
    'post_status' => array('publish',  'draft')
    
    ) );
    ?><ul>
    <?php



    if ( $posts->have_posts() ) :
    
    while ( $posts->have_posts() ) :
    
    $posts->the_post(); 
    
    $data = get_post_meta( $post->ID, '_gestus_aid_key', true );

    $starts = isset($data['start_time']) && $data['active_time'] == 1 ? $this->tool_box->reformat_time($data['start_time']) : '';

    $end = isset($data['end_time']) && $data['active_time'] == 1 ? $this->tool_box->reformat_time($data['end_time']) : '';


?>


    <div class="aid">
        <p><?php $start = $starts ?  '<br>Ansøgningsmulighed, løber <br>Fra: ' . $starts . ' <br>Til: ' . $end: '' ;?>
        </p>


        <?php
                if($value['links'] == '1' && get_post_status() != 'draft') { ?>
        <li><a href='<?php the_permalink() ?>'><?php echo  get_the_title()  ?></a> </li>
        <?php } 

                //check if is set between two user defined times and set to expire
                if (($now >= $start && $now < $end) && $data['active_time'] == 1 && get_post_status() == 'publish' &&  !isset($data['activate_aid_type'])){

                echo' <li>'.  get_the_title() .'  </li> ';

                //if they are NOT between the two times and is set to expire
                }else if(( !($now >= $start && $now < $end) ) && $data['active_time'] == 1 && get_post_status() == 'draft' && isset($data['activate_aid_type'])){
                       
                    
                //set to draft if not within time
                echo' <li>'.  get_the_title() .' '. $start.' </li> ';
                
            }


      endwhile;
      
    endif;
    ?>
</ul>
<?php
    return ob_get_clean();	 
    
    }


    

   public function img_url_list( $atts, $content ){


       

        $value = shortcode_atts( array(
        
        'posttype' => 'partnere',
        'columns'	=> 5,
        'num_posts' => -1
        
        ), $atts );
        
        ob_start();
        
        global $post;
        



        $numpost = ! $value['num_posts'] ? -1 : $value['num_posts'] ;
        
        $posts = new \WP_Query( array(
        'order'     => 'ASC',
        'orderby' => 'menu_order',
        'post_type' => $value['posttype'],
        'posts_per_page' => $numpost,
        'meta_query' => array(
    
            array(
    
                'key' => '_gestus_erhvervspartner_key',
                'value' => 's:8:"approved";i:1;',
                'compare' => 'LIKE'
            )
        )
        
        ) );
        
        $opt = get_option('gestus_page_manager');


        $i = 0;
        $numcols = isset($opt['num_logo_in_columns']) ? $opt['num_logo_in_columns'] : 4;

        if(wp_is_mobile()){
        $numcols = isset($opt['num_logo_columns_mobile']) ? $opt['num_logo_columns_mobile'] : 4;
        }    

        
        if ( $posts->have_posts() ) :



        while ( $posts->have_posts() ) :
        
        $posts->the_post(); 	

        
        if ($i % $numcols == 0 || $i == 0) {
            echo '<div class="et_pb_section et_pb_section_'.$numcols.' et_section_regular">';
            echo '<div class="et_pb_row et_pb_row_6 et_pb_row_'.$numcols.'col">';

            
          }
        
        $data = get_post_meta( get_the_ID(), '_gestus_erhvervspartner_key', true );

        $i++;
        ?>

<?php

        
    
        $target = !get_the_content()  ? 'target="_blank"' : '';


        
        ?>


<?php 
        if ($i % $numcols == 0) {
            $last_child = '';
            
                  
        } ?>




        <div class="et_pb_column et_pb_column_1_<?php echo $numcols ?> et_pb_column_13  et_pb_css_mix_blend_mode_passthrough <?php echo $last_child?>">			
            <div class="et_pb_module et_pb_image et_pb_image_1 et_pb_has_overlay">
                <a href="<?php echo $data['website'] ?>" <?php echo $target ?>>
                    <span class="et_pb_image_wrap has-box-shadow-overlay">
                        <div class="box-shadow-overlay"></div>
                            <img src="<?php echo $data['logo'] ?>" style="border-radius: 5px;box-shadow: 0px 5px 3px #aaaaaa;" alt="<?php echo get_the_title()?>'s Logo" title="<?php echo get_the_title()?>'s Logo">
                        <span class="et_overlay et_pb_inline_icon" style="border-radius: 5px; background-color: rgba(0, 156, 162, 0.5);" data-icon="="></span>
                    </span>
                </a>
            </div>
        </div> <!-- .et_pb_column -->
       <?php 
        if ($i % $numcols == 0) {
            //$last_child = 'et-last-child';
                    echo '</div></div>';
        } ?>
        
        <?php 
        
        endwhile; 

        endif;

              ?>               


<?php
        return ob_get_clean();	  

 
} 

        public function posts_by_taxonomy()
        {

           
            $terms = get_terms('faqs');

            
            foreach($terms as $term) {



                $posts = get_posts(array(
                        'post_type' => 'faq',
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'faqs',
                                'field' => 'slug',
                                'terms' => $term->slug
                            )
                        ),
                        'numberposts' => -1
                    ));
                foreach($posts as $post) {
                   
                }
            }
        }

    // Display current year 
    public function year_shortcode() {

        ob_start();
            $year = date_i18n('Y');
                echo $year; 
        return ob_get_clean();	  
    } 


    public function count_posts()
    {
        ob_start();
         $jobs = new WP_Query(array( 'post_type' => 'erhvervspartner' ));
        if ($jobs->have_posts()) { 
        
            $count_posts = wp_count_posts('erhvervspartner')->publish; 

            echo $count_posts;
        
        } 
        return ob_get_clean();	  
    }







}

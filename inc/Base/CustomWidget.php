<?php

namespace Inc\Base;

class CustomWidget
{

    public function register()
    {


        //add_action('widgets_init', array($this , 'my_function'));
    }

   public function my_function(  )
    {
        
        register_sidebar(array(
            'name' => __('slider_area1', 'gestus'),
            'id' => 'slider_area1', // unique-sidebar-id
            'description' => '',
            'class' => '',
            'before_widget' => '<li id="%1$s" class="widget %2$s">',
            'after_widget' => '</li>',
            'before_title' => '<h2 class="widgettitle">',
            'after_title' => '</h2>',
        ));

        register_sidebar(array(
            'name' => __('slider_area2', 'gestus'),
            'id' => 'slider_area2', // unique-sidebar-id
            'description' => '',
            'class' => '',
            'before_widget' => '<li id="%1$s" class="widget %2$s">',
            'after_widget' => '</li>',
            'before_title' => '<h2 class="widgettitle">',
            'after_title' => '</h2>',
        ));

        register_sidebar(array(
            'name' => __('slider_area3', 'gestus'),
            'id' => 'slider_area3', // unique-sidebar-id
            'description' => '',
            'class' => '',
            'before_widget' => '<li id="%1$s" class="widget %2$s">',
            'after_widget' => '</li>',
            'before_title' => '<h2 class="widgettitle">',
            'after_title' => '</h2>',
        ));
        
    }
    
    

}

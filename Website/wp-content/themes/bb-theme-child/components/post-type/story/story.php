<?php

////////////////////
// CPT: Dev Pages //
////////////////////

add_action( 'init', 'cpt_register_story' );
function cpt_register_story() {
    register_post_type( 'story',
        array(
            'labels' => array(
                'name' => __( 'Stories' ),
                'singular_name' => __( 'Story' ),
            ),
            'public' => true,
            'exclude_from_search' => true,
            'menu_position' => 2,
            'menu_icon' => 'dashicons-welcome-write-blog',
            'has_archive' => false,
            'supports' => array( 'title', 'editor', 'thumbnail'),
            'show_in_rest' => true
        )
    );
}

///////////////////
// Taxo: Format  //
///////////////////

add_action( 'init', 'taxo_register_format', 0 );
function taxo_register_format() {
 
  $labels = array(
    'name' => _x( 'Format', 'taxonomy general name' ),
    'singular_name' => _x( 'Format', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Format' ),
    'all_items' => __( 'All Format' ),
    'parent_item' => __( 'Parent Format' ),
    'parent_item_colon' => __( 'Parent Format:' ),
    'edit_item' => __( 'Edit Format' ), 
    'update_item' => __( 'Update Format' ),
    'add_new_item' => __( 'Add New Format' ),
    'new_item_name' => __( 'New Format Name' ),
    'menu_name' => __( 'Format' ),
  );    
 
  register_taxonomy('story_format',array('story'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_in_rest' => true,
    'show_admin_column' => true,
    'rewrite' => array( 'slug' => 'story_format' ),
    'query_var' => true,
    "public" => false,
    "publicly_queryable" => false,
    "show_admin_column" => false,
    "show_in_rest" => true,
    "rest_base" => "format",
    "rest_controller_class" => "WP_REST_Terms_Controller",
    "show_in_quick_edit" => false,
  ));
 
}

//////////////////
// Taxo: Topic  //
//////////////////

add_action( 'init', 'taxo_register_topic', 0 );
function taxo_register_topic() {
 
  $labels = array(
    'name' => _x( 'Topic', 'taxonomy general name' ),
    'singular_name' => _x( 'Topic', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Topic' ),
    'all_items' => __( 'All Topic' ),
    'parent_item' => __( 'Parent Topic' ),
    'parent_item_colon' => __( 'Parent Topic:' ),
    'edit_item' => __( 'Edit Topic' ), 
    'update_item' => __( 'Update Topic' ),
    'add_new_item' => __( 'Add New Topic' ),
    'new_item_name' => __( 'New Topic Name' ),
    'menu_name' => __( 'Topic' ),
  );    
 
  register_taxonomy('story_topic',array('story'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_in_rest' => true,
    'show_admin_column' => true,
    'rewrite' => array( 'slug' => 'story_topic' ),
    'query_var' => true,
    "public" => false,
    "publicly_queryable" => false,
    "show_admin_column" => false,
    "show_in_rest" => true,
    "rest_base" => "format",
    "rest_controller_class" => "WP_REST_Terms_Controller",
    "show_in_quick_edit" => false,
  ));
 
}
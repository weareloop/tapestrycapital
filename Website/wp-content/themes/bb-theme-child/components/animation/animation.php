<?php 

add_action( 'wp_enqueue_scripts', 'salient_child_enqueue_styles');
function salient_child_enqueue_styles() {
	
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css', array('font-awesome'));
    wp_enqueue_script('script',get_stylesheet_directory_uri() . '/script.js',array( 'jquery' ),rand());

    if ( is_rtl() ) 
   		wp_enqueue_style(  'salient-rtl',  get_template_directory_uri(). '/rtl.css', array(), '1', 'screen' );
    
}

// Animated Design
  function animation_render() {
	  $Vdata = file_get_contents('/animation.html');
    return $Vdata;
}
add_shortcode('animation', 'animation_render');


?>
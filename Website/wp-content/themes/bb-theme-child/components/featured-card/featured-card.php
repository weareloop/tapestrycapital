<?php

function get_primary_term_id_yoast( $post_id, $taxonomy ) {
    // Check if Yoast SEO is active and the WPSEO_Primary_Term class exists
    if ( class_exists( 'WPSEO_Primary_Term' ) ) {
        $wpseo_primary_term = new WPSEO_Primary_Term( $taxonomy, $post_id );
        $primary_term_id = $wpseo_primary_term->get_primary_term();
        if ( ! is_wp_error( $primary_term_id ) && ! empty( $primary_term_id ) ) {
            return $primary_term_id;
        }
    }
    return false;
}



// Scrolling Row Dynamic //
add_shortcode( 'scrolling_row_dynamic', 'scrolling_row_dynamic' );
function scrolling_row_dynamic($atts){
	
	
    $a = shortcode_atts(array(
            'type' => '',
            'id' => '',
            'category_name' => '',
            'category_term' => ''
        ), $atts);
        $id = (isset($atts['id']) && $atts['id']!="") ? explode(",", $atts['id']) : '';
        $post_type = (isset($atts['type'])) ? explode(",", $atts['type']) : "story";
        $category_name = (isset($atts['category_name'])) ? $atts['category_name'] : "";
        $category_term = (isset($atts['category_term'])) ? $atts['category_term'] : "";
            $category_term = explode(",",$category_term);
    
    
        // Query Construction
        $args = array(
            'numberposts'      => -1,
            'orderby'          => 'title',
            'order'            => 'ASC',
            'post_type'        => $post_type

        );
        
        if ($id!="") {
            $args = array(
                'numberposts'      => -1,
                'category'         => 0,
                //'orderby'          => 'title',
                //'order'            => 'ASC',
                'post_type'        => $post_type,
                'include'          => $id
            );
        } else {
            if (!empty($category_name) && !empty($category_term)) {
                $args = array(
                    'numberposts'      => -1,
                    'category'         => 0,
                    'orderby'          => 'title',
                    'order'            => 'ASC',
                    'post_type'        => $post_type,
                    'tax_query' => array(
                        array(
                            'taxonomy' => $category_name,
                            'field'    => 'slug',
                            'terms'    => $category_term,
                            'operator' => 'IN',
                        )
                    )
                );
            } else {
                $args = array(
                    'numberposts'      => -1,
                    'category'         => 0,
                    'orderby'          => 'title',
                    'order'            => 'ASC',
                    'post_type'        => $post_type
                );
            }
        }

        $posts = get_posts($args);

    ob_start();



        if (count($posts)) {
            foreach ($posts as $post) {
                
                $website = (get_field("external_link", $post->ID)) ? get_field("external_link", $post->ID) : get_permalink($post->ID);
                $terms = get_the_terms( $post->ID, 'story_format' );
                if (count($terms)>0) {
                    $terms_array = array();
                    foreach ( $terms as $term ) {
                        $terms_array[] = $term->name;
                    }
                }
                $desc = get_field("description", $post->ID);
                // Use this method for  PRIMARY TAXONOMY
                $primary_term_id = get_primary_term_id_yoast( $post->ID, 'story_topic' );
                
                echo "<a class='scrolling_row_website' href='".$website."' target='_blank'>";
                echo "<div class='scrolling_row_item'>";
                echo "<div class='scrolling_row_image'>";
                    echo get_the_post_thumbnail($post->ID,"thumbnail");
                    if (count($terms)>0) {
                        echo "<div class='scrolling_row_icons'>";
                        foreach ( $terms as $term ) {
                            $primary_term_class="";
                            if ($primary_term_id == $term->term_id || count($terms)==1) $primary_term_class = "primary";
                            echo "<div class='scrolling_row_icon ".$term->slug." ".$primary_term_class."'>".$term->name."</div>";
                        }   
                        echo "</div>";
                    }
                echo "</div>";
                echo "<div class='scrolling_row_title'><h3 class='h4'>".$post->post_title."</h3>";
                    if ($desc) {
                        echo "<div class='scrolling_row_cats'>".$desc."</div>";
                    }
                echo "</div>";
                echo "</div>";
                echo "</a>";
            }
        }
        else echo "<div style='color:white;height:200px;'>No results!</div>";


    return ob_get_clean();
    
}


?>
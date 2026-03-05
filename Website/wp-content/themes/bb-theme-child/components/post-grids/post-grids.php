<?php
    
    //[post-wedge]
add_shortcode('post-wedge', 'post_wedge_function');

function post_wedge_function($atts)
{

    $a = shortcode_atts(array(
        'type' => '',
        'layout' => '',
        'id' => '',
        'color' => '',
        'category_name' => '',
        'category_term' => ''
    ), $atts);
    $color = (isset($atts['color'])) ? $atts['color'] : "cream";
    $id = (isset($atts['id'])) ? explode(",", $atts['id']) : '';
    $layout = (isset($atts['layout'])) ? $atts['layout'] : "flex";
    $post_type = (isset($atts['type'])) ? explode(",", $atts['type']) : "news";
    $number_post = ($layout != "two-column") ? 3 : 2;
    $category_name = (isset($atts['category_name'])) ? $atts['category_name'] : "";
    $category_term = (isset($atts['category_term'])) ? $atts['category_term'] : "";
    ob_start();

    $args = array(
        'numberposts'      => $number_post,
        'orderby'          => 'date',
        'order'            => 'DESC',
        'post_type'        => $post_type

    );
    if (get_post_type(get_the_ID()) == 'post') {
        $id = get_field('relative_feed');
    }
    if (!empty($id)) {
        $args = array(
            'numberposts'      => $number_post,
            'category'         => 0,
            'orderby'          => 'date',
            'order'            => 'DESC',
            'post_type'        => $post_type,
            'include'          => $id
        );
    } else {
        if (!empty($category_name) && !empty($category_term)) {
            $args = array(
                'numberposts'      => $number_post,
                'category'         => 0,
                'orderby'          => 'date',
                'order'            => 'DESC',
                'post_type'        => $post_type,
                'tax_query' => array(
                    array(
                        'taxonomy' => $category_name,
                        'field'    => 'slug',
                        'terms'    => $category_term
                    )
                )
            );
        } else {
            $args = array(
                'numberposts'      => $number_post,
                'category'         => 0,
                'orderby'          => 'date',
                'order'            => 'DESC',
                'post_type'        => $post_type
            );
        }
    }

    $posts = get_posts($args);


    if (count($posts)) {
        echo "<div class='post-wedge " . $color . ' ' . $layout . "'>";
        //echo '<pre>'; print_r($args); echo '</pre>';
        //echo '<pre>'; print_r($posts); echo '</pre>';
        foreach ($posts as $val) {
            $btn_txt = 'Read More';
            $link_url = empty(get_field("external_link", $val->ID)) ? get_permalink($val->ID) : get_field("external_link", $val->ID);
            $external = !empty(get_field('external_link', $val->ID));
            $link_target = empty(get_field("external_link", $val->ID)) ? '_self' : '_blank';
            $featured_img_id = get_post_thumbnail_id($val->ID);
            $terms = get_the_terms($val->ID, "story_format");
            if (count($terms)>0) {
                $terms_array = array();
                foreach ( $terms as $term ) {
                    $terms_array[] = $term->name;
                }
            }

        ?>
            <div class="post post-row">
                <div class="post-col img-col">
                    <?php echo (get_the_post_thumbnail($val->ID) == "") ? '<div class="defaultImg"></div>' : wp_get_attachment_image($featured_img_id, 'medium', "", array("class" => "post-grid-img")); ?>
                    <?php
                    if ($terms) {
                        echo '<div class="tags"><span>' . $terms[0]->name . '</span></div>';
                    }
                    ?>
                </div>
                <div class="post-col title-col">
                    <?php
                    if ($terms) {
                        echo '<div class="tags"><span>' . $terms[0]->name . '</span></div>';
                    }
                    ?>
                    <h3><?php echo $val->post_title; ?></h3>
                    <p class="date-col"><?php echo date('F d, Y', strtotime($val->post_date)); ?></p>
                    <a href="<?php echo $link_url; ?>" target=<?php echo $link_target; ?> class="buttonlink arrow_right arrow_white rect_left rect_teal" role="button">
                        <span class="fl-button-text"><?php echo $btn_txt; ?></span>
                    </a>
                </div>
            </div>
        <?php
        }
        echo "</div>";
    }
    return ob_get_clean();
}

/////////////////////
//Post Grid Filter //
/////////////////////

//filter for post with ajax
add_shortcode( 'filter_ajax', 'filter_ajax_function' );
function filter_ajax_function($atts) {

    $param = shortcode_atts(array(
        'type' => '',
        'layout' => '',
        'search_label' => '',
        'search_input' => '',
        'button' => '',
        'hint' => '',
        'clear' => ''
    ), $atts);
    
    $post_type = (isset($atts['type'])) ? explode(',', $atts['type']) : "post";
    $layout = (isset($atts['layout'])) ? $atts['layout'] : "foldable";
    $search_label = (isset($atts['search_label'])) ? $atts['search_label'] : "Search";
    $search_input = (isset($atts['search_input'])) ? $atts['search_input'] : "Type your query";
    $button = (isset($atts['button'])) ? $atts['button'] : "Apply Filter";
    $hint = (isset($atts['hint'])) ? $atts['hint'] : "";
    $clear = (isset($atts['clear'])) ? $atts['clear'] : "Clear All";
    ob_start();

    //Search
    if($_GET['search'] && !empty($_GET['search'])) {
        $text = $_GET['search'];
    }
    //taxonomies
    $taxs = get_object_taxonomies( $post_type );
    $tax_type = [];
    foreach($_GET as $key=>$filter){
        if($key!='search'&&$key!='clearAll'){
            $tax_type[$key] = $filter;
        }
    }
    $cats = explode("&", explode("?", $_SERVER['REQUEST_URI'])[1]);
    $param_tax_value = [];
    $param_txt = '';
    foreach($cats as $cat){
        if(explode(":", $cat)[0]=='text'){
            $param_txt = explode(":", $cat)[1];
        }else{
            $param_tax_value[] = explode(":", $cat)[1];
        }
    }
    ?>


        <div class="fl-archive--filter closed <?php echo $layout; ?>">
            
            <form method="GET" action="/" class="postsFilterForm">

                    <div class="filter-form--content hidden">
                        <div class="fl-archive--filter-search">
                            <div>
                                <?php $searchid = uniqid(); ?>
                                <label class="<?php if(empty($search_label)){echo 'hidden';} ?>" for="<?php echo "search".$searchid; ?>"><?php if(!empty($search_label)){echo $search_label;} ?></label>
                                <input 
                                    id="<?php echo "search".$searchid; ?>"
                                    type="text" 
                                    name="search" 
                                    placeholder="<?php echo $search_input; ?>" 
                                    value="<?php echo $param_txt; ?>"
                                />
                            </div>
                            
                            <div class="category-filters--go">
                                <button><?php echo $button; ?></button>
                            </div>
                        </div> 
                        <div class="fl-archive--filter-items">
                            <!-- Taxonomies loop -->
                            <?php foreach($taxs as $key=>$tax){ 
                                if ($tax!='focus'&& $tax!='translation_priority'){?>
                                <div class="filter-item <?php echo $tax; ?>">
                                    <label class="filter-item--label-title" tabindex="0">
                                    <?php echo get_taxonomy($tax)->labels->singular_name; ?>
                                    </label>
                                    <?php
                                        $taxonomies = get_terms( array(
                                        'taxonomy' => $tax,
                                        'orderby'  => 'name',
                                        'order'    => 'ASC',
                                        'hide_empty' => true ) );
                                    ?>
                                    <?php  if(!empty($hint)){
                                        echo '<p class="hint">'.$hint.'</p>';
                                    } ?>
                                    <ul>
                                    <?php foreach($taxonomies as $taxonomy) { 
                                        if($taxonomy->slug!='uncategorized'){
                                    ?>
                                        <li class="filter_taxo_item">
                                            <label tabindex="0" for='<?php echo $taxonomy->slug; ?>'>
                                                <input class="filter_taxo_item_input" tabindex='-1' id='<?php echo $taxonomy->slug; ?>' 
                                                    type="checkbox" 
                                                    name="tax<?php echo $key; ?>[]" 
                                                    data-taxonomy="<?php echo $tax;?>" 
                                                    value="<?php echo $taxonomy->slug; ?>"
                                                    <?php checked(
                                                        (isset($param_tax_value) && in_array($taxonomy->slug, $param_tax_value))
                                                    ) ?>
                                                />
                                                <span class="checkmark"></span>
                                                <span><?php echo $taxonomy->name; ?></span>
                                            </label>
                                        </li>
                                    <?php }} ?>
                                    </ul>
                                </div>
                            <?php }} ?>

                        </div>
                          
                    </div>

                    <div class="filter-form--header">
                        <div class="fl-archive--filter-refine" tabindex="0">
                            <span class="h4">
                                <?php echo 'Filter Your Search'; ?>
                            </span>
                        </div>
                        <div class="fl-archive--filter-clear">
                            <span tabindex="0"><?php echo $clear; ?></span>
                        </div>

                    </div>
                
            </form> 
        </div>
    <?php

    return ob_get_clean();
}

// post with ajax
add_shortcode( 'post_ajax', 'post_ajax_function' );
function post_ajax_function($atts) {

    $param = shortcode_atts(array(
        'post_type' => '',
        'layout' => '',
        'readmore' => '',
        'button_label' => '',
        'numberperpage' => ''
    ), $atts);

    $post_type = !empty($param['post_type'])? explode(',', $param['post_type']) : "thefeed";
    $layout = !empty($param['layout'])? $param['layout'] : "grid";
    $button_label = !empty($param['button_label'])? $param['button_label'] : "View resource";
    $readmore = !empty($param['readmore'])? $param['readmore'] : "readmore";
    $numberperpage = !empty($param['numberperpage'])? $param['numberperpage'] : "9";
    ob_start();
    
    $cats = explode("&", explode("?", $_SERVER['REQUEST_URI'])[1]);
    $tax_query = array('relation' => 'AND');
    $text = '';
    $post_filter = [];
    /*
    foreach($cats as $cat){
        if(explode(":", $cat)[0]=='text'){
            $text = explode(":", $cat)[1];
        }else{
            
            $taxonomy_name = explode(":", $cat)[0];
            $catSlug = explode(":", $cat)[1];
            if($taxonomy_name == 'post'){
                $post_filter[] = $catSlug;
            }else{
                if(!empty($taxonomy_name)&&!empty($catSlug)){
                    $tax_query[] =  array(
                        'taxonomy'  => $taxonomy_name,
                        'field'     => 'slug',
                        'terms'     => $catSlug,
                        'operator' => 'IN'
                    );
                }
            }
        }
    }
    */

    $taxs = get_object_taxonomies( $post_type );

    foreach($taxs as $tax){
        if($tax != 'focus'){
            $terms = [];

            foreach($cats as $cat){
                if(explode(":", $cat)[0]=='text'){
                    $text = explode(":", $cat)[1];
                }else{
                    $taxonomy_name = explode(":", $cat)[0];
                    $catSlug = explode(":", $cat)[1];
                    if($taxonomy_name == 'post'){
                        $post_filter[] = $catSlug;
                    }else{
                        if(!empty($taxonomy_name)&&!empty($catSlug)){
                            if($taxonomy_name==$tax){
                                $terms[] = $catSlug;
                                //echo '<br>'.$tax.' '.$catSlug.'<br>';
                            }
                        }
                    }
                }
            }
            if(!empty($terms)){
                $tax_query[] =  array(
                                'taxonomy'  => $tax,
                                'field'     => 'slug',
                                'terms'     => $terms,
                                'operator' => 'IN'
                );
            }

            //var_dump($tax_query);

        }
    }

    
    if(count($post_filter)>0){
        $post_type = $post_filter;
    }
    if(count($tax_query)==1 && empty($text)){
        $args = array(
            'post_type' => $post_type,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'order_by' => 'date',
            'order' => 'desc'
        );
    }elseif(count($tax_query)>1 && empty($text)){
        $args = array(
            'post_type' => $post_type,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'order_by' => 'date',
            'order' => 'desc',
            'tax_query'         => $tax_query
        );
    }elseif(count($tax_query)==1 && !empty($text)){
        $args = array(
            'post_type' => $post_type,
            'post_status' => 'publish',
            'numberposts' => -1,
            'posts_per_page' => -1,
            'order_by' => 'date',
            'order' => 'desc',
            's'                 => $text
        );
    }else{
        $args = array(
            'post_type' => $post_type,
            'post_status' => 'publish',
            'numberposts' => -1,
            'posts_per_page' => -1,
            'order_by' => 'date',
            'order' => 'desc',
            'tax_query'         => $tax_query,
            's'                 => $text
        );
    }
    //$posts = get_posts($args);

    add_filter('trp_force_search', '__return_true'); 
    //$response.=$postType;
    $query = new WP_Query($args);
    $posts = $query->posts;
    //$posts = get_posts($args);

    ?>  <div class="loading"><img src="/wp-content/uploads/—Pngtree—loading-icon-dot-ring-vector_8462422-scaled.png" alt=""></div>
        <div class="posts-container" data-readmore="<?php echo $readmore ?>" data-numberperpage="<?php echo $numberperpage ?>" data-post="<?php echo $param['post_type']; ?>">
            <?php 
            $url = "";
            if($layout=="list"){
                $url = 'layouts/post_list.php';
            }elseif($layout=="card"){
                $url = 'layouts/post_card.php';
            }else{
                $url = 'layouts/post_grid.php';
            } 
            include($url);
            ?>
        </div>
    <?php

    wp_reset_query();

    return ob_get_clean();
}


//filter function for post with ajax
function filter_projects() {
    $catString = $_POST['category'];
    $txtString = $_POST['text'];
    $gridType = $_POST['grid'];
    $readmore = $_POST['readmore'];
    $numberperpage = $_POST['numberperpage'];
    $postType = !empty($_POST['postType'])? explode(',', $_POST['postType']) : "post";
    $response = '';
    
    $text = explode(":", $txtString)[1];

    $catTerms = explode("&", trim($catString));
    $tax_query = array('relation' => 'AND');
    $filter_post = [];

    /*
    foreach($catTerms as $catTerm){
        
        if($catTerm!=""){
            $taxonomy_name = explode(":", $catTerm)[0];
            $catSlug = explode(":", $catTerm)[1];
            if($taxonomy_name == 'post'){
                $filter_post[] = $catSlug;
            }else{
                $tax_query[] =  array(
                    'taxonomy'  => $taxonomy_name,
                    'field'     => 'slug',
                    'terms'     => $catSlug,
                    'operator' => 'IN'
                );
            }
        }
    }
    */

    $taxs = get_object_taxonomies( $postType );

    foreach($taxs as $tax){
        if($tax != 'focus'){
            $terms = [];

            foreach($catTerms as $cat){
                if(explode(":", $cat)[0]=='text'){
                    $text = explode(":", $cat)[1];
                }else{
                    $taxonomy_name = explode(":", $cat)[0];
                    $catSlug = explode(":", $cat)[1];
                    if($taxonomy_name == 'post'){
                        $filter_post[] = $catSlug;
                    }else{
                        if(!empty($taxonomy_name)&&!empty($catSlug)){
                            if($taxonomy_name==$tax){
                                $terms[] = $catSlug;
                            }
                        }
                    }
                }
            }
            if(!empty($terms)){
                $tax_query[] =  array(
                                'taxonomy'  => $tax,
                                'field'     => 'slug',
                                'terms'     => $terms,
                                'operator' => 'IN'
                );
            }

        }
    }

    if(count($filter_post)>0){
        $postType = $filter_post;
    }
    if(empty($text)){
        $args = array(
            'post_type' => $postType,
            'numberposts' => -1,
            'posts_per_page' => -1,
            'order_by' => 'date',
            'order' => 'desc',
            'post_status' => 'publish',
            'tax_query'         => $tax_query
        );
    }
    else{
        $args = array(
            'post_type' => $postType,
            'numberposts' => -1,
            'posts_per_page' => -1,
            'order_by' => 'date',
            'order' => 'desc',
            'post_status' => 'publish',
            'tax_query'         => $tax_query,
            's'                 => $text
        );
    }

            
    //$ajaxposts =  get_posts($args);
    
    //add_filter('trp_force_search', '__return_true'); 
    //$response.=$postType;

    $query = new WP_Query($args);
    $posts = $query->posts;
    //$posts = get_posts($args);

    
    if($gridType=="list"){
        include('layouts/post_list.php');
    }elseif($gridType=="card"){
        include('layouts/post_card.php');
    }elseif($gridType=="grid"){
        include('layouts/post_grid.php');
    }else{
        include('layouts/post_grid_news.php');
    }


    if(!count($posts)){
        echo "<p class='h2 noresult'>No results found. Please refine your search.</p>";
    }

    wp_reset_query();

    
    exit;
  }
  add_action('wp_ajax_filter_projects', 'filter_projects');
  add_action('wp_ajax_nopriv_filter_projects', 'filter_projects');


 /////////
// Tag //
/////////

function post_tag(){
        
    ob_start();
        
    $id = get_the_ID();
    if(!empty(get_the_terms($id, 'story_format')[0])){
        echo '<tag>'.get_the_terms($id, 'story_format')[0]->name.'</tag>';
    }
        
    return ob_get_clean();
}
add_shortcode( 'post_tag', 'post_tag' );

//add social share for post
function post_share()
{

    $url_encoded = urlencode((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");

    ob_start();
?>
    <div class="share_icons">
        <span>Share</span>
        <a class="share_icon" href="https://www.linkedin.com/sharing/share-offsite/?url=<? echo $url_encoded; ?>" target="_blank" onclick="window.open(this.href,'social-share','left=20,top=20,width=500,height=500,toolbar=1,resizable=0');return false;">
            <img src="/wp-content/uploads/icon-linkedin-red.svg" alt="LinkedIn icon">
        </a>
        <a class="share_icon" href="https://www.facebook.com/sharer.php?u=<? echo $url_encoded; ?>" target="_blank" onclick="window.open(this.href,'social-share','left=20,top=20,width=500,height=500,toolbar=1,resizable=0');return false;">
            <img src="/wp-content/uploads/icon-facebook-red.svg" alt="Facebook icon">
        </a>
        </a>
    </div>
<?
    return ob_get_clean();
}
add_shortcode('share', 'post_share');
?>


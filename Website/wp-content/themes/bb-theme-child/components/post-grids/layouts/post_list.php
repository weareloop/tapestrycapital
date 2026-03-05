<?php
$layout = 'list';
$readmore = (isset($_GET['readmore'])) ? $_GET['readmore'] : 'readmore';
$numberperpage = (isset($_GET['numberperpage'])) ? intval($_GET['numberperpage']) : 6;
        echo "<div class='posts ".$layout."'>";
            foreach($posts as $key=>$post){
                $terms = get_post_type_object(get_post_type( $post->ID ))->labels->singular_name;
                $category_detail = get_the_category($post->ID);
                $external = get_field("external", $post->ID);
                $desc_tog = 0;
                $desc = '';
                if(!empty(get_field("short_description", $post->ID))) {
                    $desc = get_field("short_description", $post->ID);
                    $desc_tog = 1;
                } 
                if(!empty(substr(get_post_meta($post->ID, '_yoast_wpseo_metadesc', true), 0, 100))) {
                    $desc = substr(get_post_meta($post->ID, '_yoast_wpseo_metadesc', true), 0, 100);
                    $desc_tog = 1;
                }
                $target = "_self";
                if(get_field("link_to", $post->ID)=='pdf'){
                    $link_url = get_field("pdf", $post->ID);
                    $target = '_blank';
                }elseif(get_field("link_to", $post->ID)=='external'){
                    $link_url = get_field("link_url", $post->ID);
                    $target = '_blank';
                }else{
                    $link_url = get_permalink($post->ID);
                    $target = '_self';
                }
            ?>
            <div class="post">
                <div class="postBox">
                    <div class="postTop">
                        <div class="imgBox">
                            <?php
                                 echo (get_the_post_thumbnail( $post->ID, 'thumbnail')=="")?'<div class="defaultImg"></div>':get_the_post_thumbnail( $post->ID, 'thumbnail'); 
                            ?>
                        </div>
                    </div>
                    <div class="postBottom">
                        <?php if($terms!='Page'){ ?>
                            <div class="metaBox">
                                <div class="tags">
                                    <?php echo $terms; ?>  
                                </div>
                                <p class="infoPost">
                                    <?php echo date('M d Y', strtotime($post->post_date)); ?>
                                </p>
                            </div>
                        <?php } ?>

                        <div class="infoBox">
                                <h3 class="h4">
                                    <a href="<?php echo $link_url; ?>" target="<?php echo $target; ?>">
                                        <?php echo $post->post_title; ?>
                                    </a>
                                </h3>
                                <?php if($desc_tog){ ?>
                                <div class="excerpt"><?php echo $desc; ?></div>
                                <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php }
                echo "</div>";
            ?>

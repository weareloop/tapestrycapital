<?php
$layout = 'grid-news';
$readmore = (isset($_GET['readmore'])) ? $_GET['readmore'] : 'readmore';
$numberperpage = (isset($_GET['numberperpage'])) ? intval($_GET['numberperpage']) : 9;
        echo "<div class='posts ".$layout."'>";
            foreach($posts as $key=>$post){
                    
                    // Only taxonomy "TYPE"
                    
                    if (get_post_type($post->ID) == "story")
                        $taxo_type = get_the_terms($post->ID, 'story_type');
                    else 
                        $taxo_type = get_the_terms($post->ID, 'story_type');

                    $link_url  = get_field("link_url", $post->ID);
                        if ($link_url) $final_link = $link_url;
                        else $final_link = esc_url(get_permalink( $post->ID ));
                    $btn_text  = get_field("link_label", $post->ID) ? : "Read More";
                    $link_target = get_field("link_target", $post->ID);
                        if (!$link_target) $link_target ="_self";
                        else $link_target ="_blank";
                    $desc = get_field("short_description", $post->ID);
                   
                   
                ?>
                <div class="post <?php echo "page_".(intval($key / $numberperpage) + 1); echo (intval($key / $numberperpage) ==0) ? " active visual" : "" ?> <?php if($external){echo " external_post"; }?>">
                    <div class="postBox">
                        <div class="postTop">
                            
                            <postgrid-image class="imgBox">
                                <?php if(!empty(get_the_terms( $post->ID, "story_type" )[0]->name)){ ?>
                                    <postgrid-tag class="tag-resource-card">
                                        <div class="fl-rich-text"><p><?php echo get_the_terms( $post->ID, "story_type" )[0]->name; ?></p></div>
                                    </postgrid-tag>
                                <?php } ?>
                                <?php   echo (get_the_post_thumbnail( $post->ID, 'thumbnail')=="")?'<div class="defaultImg"></div>':get_the_post_thumbnail( $post->ID, 'thumbnail');    ?>
                            </postgrid-image>
                        </div>
                        
                        <postgrid-title><a class="news_item_link" target="<?php echo $link_target; ?>" href="<?php echo $final_link; ?>"><h3 class="h4"><?php echo $post->post_title; ?></h3></a></postgrid-title>
                        <?php if ($desc) { ?><postgrid-description><p class="small"><?php echo $desc; ?></p></postgrid-description><? } ?>
                        <postgrid-date class="date"><?php echo date('d M Y', strtotime($post->post_date)); ?><postgrid-date>
                        
                    </div>
                </div>
                <?php 
            }
                echo "</div>";
            ?>
            <?php if($readmore == "readmore"){ ?>
                <div class="loadMore">
                    <button class="loadMore_btn <?php echo $layout; ?> perpage_<?php echo $numberperpage; ?>">
                        <?php echo 'Load More'; ?>
                    </button>
                </div>
            <?php }elseif($readmore == "pagination"){ ?>
                <div class="loadMore">
                    <?php 
                    for($i = 0; $i < intval(count($posts) / $numberperpage)+1; $i++){
                    ?>
                        <button class="page_btn <?php if($i==0){ echo ' active'; } ?> "><?php echo $i+1; ?></button>
                    <?php
                    }
                    ?>
                </div>
            <?php } ?>
<?php
$layout = 'card';
$readmore = (isset($_GET['readmore'])) ? $_GET['readmore'] : 'readmore';
$numberperpage = (isset($_GET['numberperpage'])) ? intval($_GET['numberperpage']) : 6;
        echo "<div class='posts ".$layout."'>";
            foreach($posts as $key=>$post){
                    $btn_text  = "Read More";
                    $desc = get_field("desc_feed", $post->ID);
                    $link_type = get_field("ext_url_feed", $post->ID);

                    if(get_field("link_to", $post->ID)=='pdf'){
                        $link_url = get_field("pdf", $post->ID);
                        $link_target = '_blank';
                    }elseif(get_field("link_to", $post->ID)=='external'){
                        $link_url = get_field("link_url", $post->ID);
                        $link_target = '_blank';
                    }else{
                        $link_url = get_permalink($post->ID);
                        $link_target = '_self';
                    }

                    
                ?>
                <div class="post <?php echo "page_".(intval($key / $numberperpage) + 1); echo (intval($key / $numberperpage) ==0) ? " active visual" : "" ?> <?php if($external){echo " external_post"; }?>">
                    <div class="postBox">
                        <div class="postTop">
                            <div class="imgBox">
                                <?php if(!empty(get_the_terms( $post->ID, "type" )[0]->name)){ ?>
                                    <div class="tag-resource-card"><p><?php echo get_the_terms( $post->ID, "type" )[0]->name; ?></p></div>
                                <?php } ?>
                                <?php   echo (get_the_post_thumbnail( $post->ID, 'thumbnail')=="")?'<div class="defaultImg"></div>':get_the_post_thumbnail( $post->ID, 'thumbnail');    ?>
                            </div>
                        </div>
                        <div class="postBottom">
                                <h3 class="h4"><?php echo $post->post_title; ?></h3>
                                <div class="desc"><?php echo $desc; ?></div>
                                <div class="date"><?php echo date('d M Y', strtotime($post->post_date)); ?></div>
                                <a target="<?php echo $target; ?>" href="<?php echo $url; ?>"><?php echo $btn_text; ?></a>
                        </div>
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